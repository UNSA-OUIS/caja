<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Illuminate\Http\Request;
use Luecano\NumeroALetras\NumeroALetras;

class FacturaController extends Controller
{
    private $empresa;

    function __construct()
    {
        $direccion_empresa = (new Address())
            ->setUbigueo(config('caja.direccion.ubigeo'))
            ->setDepartamento(config('caja.direccion.departamento'))
            ->setProvincia(config('caja.direccion.provincia'))
            ->setDistrito(config('caja.direccion.distrito'))
            ->setUrbanizacion(config('caja.direccion.urbanizacion'))
            ->setDireccion(config('caja.direccion.direccion'))
            ->setCodLocal(config('caja.direccion.codigo_local')); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

        $this->empresa = (new Company())
            ->setRuc(config('caja.empresa.ruc'))
            ->setRazonSocial(config('caja.empresa.razon_social'))
            ->setNombreComercial(config('caja.empresa.nombre_comercial'))
            ->setAddress($direccion_empresa);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = Comprobante::with('detalles')->where('codigo', 'like', '%' . $request->filter . '%')->where('serie', 'like', 'F' . '%');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }
    public function enviar(Comprobante $factura)
    {
        $factura = Comprobante::with('detalles')->where('id', 'like', $factura->id)->first();

        try {
            $see = require config_path('Sunat\config.php');

            // Cliente
            $client = (new Client())
                ->setTipoDoc('6')
                ->setNumDoc('10723516108')
                ->setRznSocial('Jesus Ruben Ortiz Chavez');

            // Venta
            $invoice = new Invoice();

            $total_agravadas = 0;
            $total_exonerados = 0;
            $total_inafectos = 0;
            $subtotal = 0;
            $igv = 0;

            $detalle = $factura->detalles;
            foreach ($detalle as $index => $value) {
                $items[$index] = (new SaleDetail())
                    ->setCodProducto($value['concepto_id'])
                    ->setUnidad('NIU') // Unidad - Catalog. 03
                    ->setCantidad($value['cantidad'])
                    ->setMtoValorUnitario($value['valor_unitario'])
                    ->setDescripcion('PRODUCTO - ' . $index)
                    ->setMtoBaseIgv(100.00)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv(18.00 / $value['cantidad'])
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                    ->setMtoValorVenta($value['valor_unitario'] * $value['cantidad'])
                    ->setMtoPrecioUnitario($value['valor_unitario'] + 18.00 / $value['cantidad']);
                $total_agravadas += $value['valor_unitario'] * $value['cantidad'];
                $igv += 18.00 / $value['cantidad'];
            }

            $formatter = new NumeroALetras();
            $montoLetras = $formatter->toInvoice($factura->total, 2, 'soles');

            $legend = (new Legend())
                ->setCode('1000') // Monto en letras - Catalog. 52
                ->setValue($montoLetras);

            $invoice->setDetails($items)->setLegends([$legend]);

            $invoice
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc('01') // Factura - Catalog. 01
                ->setSerie($factura->serie)
                ->setCorrelativo($factura->correlativo)
                ->setFechaEmision(new DateTime(now())) // Zona horaria: Lima
                ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
                ->setTipoMoneda('PEN') // Sol - Catalog. 02
                ->setCompany($this->empresa)
                ->setClient($client)
                ->setMtoOperGravadas($total_agravadas)
                ->setMtoIGV($igv)
                ->setTotalImpuestos($igv)
                ->setValorVenta($total_agravadas)
                ->setSubTotal($total_agravadas + $igv)
                ->setMtoImpVenta($total_agravadas + $igv);

            $result = $see->send($invoice);

            // Guardar XML firmado digitalmente.
            $xmlGuardado = file_put_contents(
                $invoice->getName() . '.xml',
                $see->getFactory()->getLastXml()
            );

            if ($xmlGuardado) {
                $factura->url_xml = $invoice->getName() . '.xml';
                $factura->update();
            }

            // Verificamos que la conexión con SUNAT fue exitosa.
            if (!$result->isSuccess()) {
                // Mostrar error al conectarse a SUNAT.
                $factura->observaciones = 'Codigo Error: ' . $result->getError()->getCode() . '\n' . 'Mensaje Error: ' . $result->getError()->getMessage();
                $factura->update();
                return $factura;
                exit();
            }

            // Guardamos el CDR
            $cdrGuardado = file_put_contents('R-' . $invoice->getName() . '.zip', $result->getCdrZip());
            if ($cdrGuardado) {
                $factura->url_cdr = 'R-' . $invoice->getName() . '.zip';
                $factura->update();
            }

            $cdr = $result->getCdrResponse();

            $code = (int)$cdr->getCode();

            if ($code === 0) {
                $factura->estado = 'aceptado';
                $factura->observaciones = $cdr->getDescription() . PHP_EOL;
                $factura->update();
                if (count($cdr->getNotes()) > 0) {
                    // Corregir estas observaciones en siguientes emisiones.
                    $factura->estado = 'observado';
                    $factura->observaciones = '';
                    $factura->update();
                    foreach ($cdr->getNotes() as $index => $value) {
                        $factura->observaciones .= json_encode('Observacion #' . $index . '=>' . $value, JSON_UNESCAPED_UNICODE) . "\n";
                    }
                    $factura->update();
                }
            } else if ($code >= 2000 && $code <= 3999) {
                $factura->estado = 'rechazado';
                $factura->observaciones = '';
                $factura->update();
            } else {
                /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
                /*code: 0100 a 1999 */
                $factura->estado = 'rechazado';
                $factura->observaciones = '';
                $factura->update();
            }

            $html = new HtmlReport();
            $html->setTemplate('invoice.html.twig');

            $report = new PdfReport($html);

            $report->setOptions([
                'no-outline',
                'viewport-size' => '1280x1024',
                'page-width' => '21cm',
                'page-height' => '29.7cm',
            ]);
            $report->setBinPath('C:\Program Files\wkhtmltopdf\bin\wkhtmltopdf.exe'); // Ruta relativa o absoluta de wkhtmltopdf

            $params = [
                'system' => [
                    'logo' => file_get_contents(public_path() . '\img\siscaja_blanco.png'), // Logo de Empresa
                    'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen
                ],
                'user' => [
                    'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                    'extras'     => [
                        // Leyendas adicionales
                        ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'],
                        ['name' => 'VENDEDOR', 'value' => 'CAJA UNSA'],
                    ],
                    'footer' => '<p>Nro Resolucion: <b>3232323</b></p>'
                ]
            ];

            $pdf = $report->render($invoice, $params);

            if ($pdf === null) {
                $error = $report->getExporter()->getError();
                echo 'Error: ' . $error;
                return;
            }

            $pdfGuardado = file_put_contents($invoice->getName() . '.pdf', $pdf);
            if ($pdfGuardado) {
                $factura->url_pdf = $invoice->getName() . '.pdf';
                $factura->update();
            }
        } catch (\Exception $e) {
            $factura->observaciones = 'Error al enviar la factura' . $e;
            $factura->update();
            return $e;
        }

        return redirect()->route('sunat.iniciarFacturas');
    }
    public function anular(Comprobante $factura)
    {
        try {
            $factura->estado = 'anulado';
            $factura->observaciones = '';
            $factura->update();
        } catch (\Exception $e) {
            $factura->observaciones = 'Error al anular la factura' . $e->getMessage();
            $factura->update();
        }

        return redirect()->route('sunat.iniciarFacturas');
    }
}
