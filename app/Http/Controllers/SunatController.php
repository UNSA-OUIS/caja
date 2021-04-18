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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Luecano\NumeroALetras\NumeroALetras;

require 'D:\OUIS\Sistema de caja e ingresos\Codigo\caja\vendor\autoload.php';

class SunatController extends Controller
{
    public function __invoke()
    {
        $noEnviado  = count(DB::table('comprobantes')
            ->where('estado', 'like', 'noEnviado')
            ->get());
        $observado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'observado')
            ->get());
        $rechazado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'rechazado')
            ->get());
        $anulado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'anulado')
            ->get());
        $aceptado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'aceptado')
            ->get());
        return Inertia::render('Sunat/Tablero', compact('noEnviado', 'observado', 'rechazado', 'anulado', 'aceptado'));
    }
    public function getEstados()
    {
        /*$noEnviado  = count(DB::table('comprobantes')
            ->where('estado', 'like', 'noEnviado')
            ->get());*/
        return 'jesus';
        /*$observado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'observado')
            ->get());
        $rechazado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'rechazado')
            ->get());
        $anulado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'anulado')
            ->get());
        $aceptado = count(DB::table('comprobantes')
            ->where('estado', 'like', 'aceptado')
            ->get());
        return Inertia::render('Sunat/Tablero', compact('noEnviado', 'observado', 'rechazado', 'anulado', 'aceptado'));*/
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexFactura(Request $request)
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBoleta(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = Comprobante::with('detalles')->where('codigo', 'like', '%' . $request->filter . '%')->where('serie', 'like', 'B' . '%');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }
    public function enviarFactura(Comprobante $factura)
    {
        $factura = Comprobante::with('detalles')->where('id', 'like', $factura->id)->first();

        try {
            $see = require storage_path() . '\app\public\config.php';

            // Cliente
            $client = (new Client())
                ->setTipoDoc('6')
                ->setNumDoc('10723516108')
                ->setRznSocial('Jesus Ruben Ortiz Chavez');

            // Emisor
            $address = (new Address())
                ->setUbigueo('150101')
                ->setDepartamento('AREQUIPA')
                ->setProvincia('AREQUIPA')
                ->setDistrito('AREQUIPA')
                ->setUrbanizacion('-')
                ->setDireccion('CALLE SANTA CATALINA 117')
                ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

            $company = (new Company())
                ->setRuc('20163646499')
                ->setRazonSocial('UNIVERSIDAD NACIONAL DE SAN AGUSTIN')
                ->setNombreComercial('UNIVERSIDAD NACIONAL DE SAN AGUSTIN')
                ->setAddress($address);

            // Venta
            $invoice = new Invoice();

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
                    ->setIgv(18.00)
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                    ->setMtoValorVenta($value['valor_unitario'] * $value['cantidad'])
                    ->setMtoPrecioUnitario($value['valor_unitario'] + $value['valor_unitario'] * 18.00);
            }

            $formatter = new NumeroALetras();
            $montoLetras = $formatter->toInvoice($factura->total);

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
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas(100.00)
                ->setMtoIGV(18.00)
                ->setTotalImpuestos(18.00)
                ->setValorVenta(100.00)
                ->setSubTotal(118.00)
                ->setMtoImpVenta($factura->total + 18.00);

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
                    //return count($cdr->getNotes());
                    // Corregir estas observaciones en siguientes emisiones.
                    $factura->estado = 'observado';
                    $factura->observaciones = json_encode($cdr->getNotes(), JSON_UNESCAPED_UNICODE);
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
            //$factura->observaciones = $cdr->getDescription() . PHP_EOL . $factura->observaciones;
            //$factura->update();

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
        }

        return redirect()->route('sunat.iniciarFacturas');
    }
    public function enviarBoleta(Comprobante $boleta)
    {
        $see = require storage_path() . '\app\public\config.php';

        try {
            // Cliente
            $client = new Client();
            $client->setTipoDoc('1')
                ->setNumDoc('46712369')
                ->setRznSocial('MARIA RAMOS ARTEAGA');

            // Emisor
            $address = new Address();
            $address->setUbigueo('150101')
                ->setDepartamento('LIMA')
                ->setProvincia('LIMA')
                ->setDistrito('LIMA')
                ->setUrbanizacion('-')
                ->setDireccion('AV LOS GERUNDIOS');

            $company = new Company();
            $company->setRuc('20000000001')
                ->setRazonSocial('EMPRESA SAC')
                ->setNombreComercial('EMPRESA')
                ->setAddress($address);

            // Venta
            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Catalog. 51
                ->setTipoDoc('03')
                ->setSerie('B001')
                ->setCorrelativo('1')
                ->setFechaEmision(new DateTime())
                ->setTipoMoneda('PEN')
                ->setClient($client)
                ->setMtoOperGravadas(100.00)
                ->setMtoIGV(18.00)
                ->setTotalImpuestos(18.00)
                ->setValorVenta(100.00)
                ->setSubTotal(118.00)
                ->setMtoImpVenta(118.00)
                ->setCompany($company);

            $item = (new SaleDetail())
                ->setCodProducto('P001')
                ->setUnidad('NIU')
                ->setCantidad(2)
                ->setDescripcion('PRODUCTO 1')
                ->setMtoBaseIgv(100)
                ->setPorcentajeIgv(18.00) // 18%
                ->setIgv(18.00)
                ->setTipAfeIgv('10')
                ->setTotalImpuestos(18.00)
                ->setMtoValorVenta(100.00)
                ->setMtoValorUnitario(50.00)
                ->setMtoPrecioUnitario(59.00);


            $formatter = new NumeroALetras();
            $montoLetras = $formatter->toInvoice($boleta->total);

            $legend = (new Legend())
                ->setCode('1000') // Monto en letras - Catalog. 52
                ->setValue($montoLetras);

            $invoice->setDetails([$item])
                ->setLegends([$legend]);

            $xml = $see->getXmlSigned($invoice);

            // Guardar XML        
            $xmlGuardado = file_put_contents($invoice->getName() . '.xml', $xml);

            if ($xmlGuardado) {
                $boleta->url_xml = $invoice->getName() . '.xml';
                $boleta->update();
            }
            $boleta->estado = 'aceptado';
            $boleta->update();
        } catch (\Throwable $th) {
            return 'Error' . $th;
        }
        return redirect()->route('sunat.iniciarBoletas');
    }
    public function anularFactura(Comprobante $factura)
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
    public function anularBoleta(Comprobante $boleta)
    {
        try {
            $boleta->estado = 'anulado';
            $boleta->observaciones = '';
            $boleta->update();
        } catch (\Exception $e) {
            Log::error('SunatController@anularBoleta, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciarBoletas');
    }
}
