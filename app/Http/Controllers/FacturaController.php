<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarCorreosJob;
use App\Models\Comprobante;
use DateTime;
use Exception;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')
            ->where('tipo_comprobante_id', 2)
            ->where('estado', 'no_enviado')
            ->whereDate('created_at', '>=', $request->fecha_inicio)
            ->whereDate('created_at', '<=', $request->fecha_fin)
            ->where('cajero_id', Auth::user()->id)->get();
        return $query;
    }
    public function enviar_facturas(Request $request)
    {
        DB::beginTransaction();
        try {
            $see = require config_path('Sunat/config.php');
            $facturas = $request->all();

            foreach ($facturas as $index => $value) {
                $factura = new Comprobante();

                $factura = Comprobante::with('comprobanteable')->with('tipo_comprobante')
                    ->with('detalles.concepto')->where('id', 'like', $value['id'])->first();

                // Cliente
                $client = (new Client())
                    ->setTipoDoc('6')
                    ->setNumDoc($factura->comprobanteable->ruc)
                    ->setRznSocial($factura->comprobanteable->razon_social);

                $invoice = new Invoice();

                $detalle = $factura->detalles;
                foreach ($detalle as $index => $value) {
                    $items[$index] = (new SaleDetail())
                        ->setCodProducto($detalle[$index]->concepto['id'])
                        ->setUnidad('NIU') // Unidad - Catalog. 03
                        ->setCantidad($value['cantidad'])
                        ->setMtoValorUnitario($value['valor_unitario'])
                        ->setDescripcion($detalle[$index]->concepto['descripcion'])
                        ->setMtoBaseIgv($value['subtotal'])
                        ->setPorcentajeIgv($value['total_impuesto']) // 18%
                        ->setIgv($value['total_impuesto'])
                        ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                        ->setTotalImpuestos($value['total_impuesto']) // Suma de impuestos en el detalle
                        ->setMtoValorVenta($value['valor_unitario'] * $value['cantidad'])
                        ->setMtoPrecioUnitario($value['valor_unitario'] + $value['total_impuesto'] / $value['cantidad']);
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
                    ->setMtoOperGravadas($factura->total_gravadas)
                    ->setMtoIGV($factura->total_impuesto)
                    ->setTotalImpuestos($factura->total_impuesto)
                    ->setValorVenta($factura->total)
                    ->setSubTotal($factura->total + $factura->total_impuesto)
                    ->setMtoImpVenta($factura->total + $factura->total_impuesto);


                $result = $see->send($invoice);

                // Guardar XML firmado digitalmente.
                $xmlGuardado = file_put_contents(
                    storage_path('app/public/Sunat/XML/' . $factura->serie . '-' . $factura->correlativo . '.xml'),
                    $see->getFactory()->getLastXml()
                );

                if ($xmlGuardado) {
                    $factura->url_xml = $factura->serie . '-' . $factura->correlativo . '.xml';
                    $factura->update();
                }

                // Verificamos que la conexión con SUNAT fue exitosa.
                if (!$result->isSuccess()) {
                    // Mostrar error al conectarse a SUNAT.
                    $resultado = ['errorMessage' => var_dump($result->getError()), 'error' => true];
                    DB::rollback();
                    return $resultado;
                }

                // Guardamos el CDR
                $cdrGuardado = file_put_contents(storage_path('app/public/Sunat/CDR/' . 'R-' . $factura->serie . '-' . $factura->correlativo . '.zip'), $result->getCdrZip());
                if ($cdrGuardado) {
                    $factura->url_cdr = 'R-' . $factura->serie . '-' . $factura->correlativo . '.zip';
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
                            $factura->observaciones .= json_encode('Observacion #' . $index . '=>' . $value, JSON_UNESCAPED_UNICODE) . "<br>";
                        }
                        $factura->update();
                    }

                    $data = [
                        'adjuntoPDF' => storage_path('app/public/Sunat/PDF/' . $factura->serie . '-' . $factura->correlativo . '.pdf'),
                        'adjuntoTicket' => storage_path('app/public/Sunat/PDF/' . $factura->serie . '-' . $factura->correlativo . '-ticket' . '.pdf'),
                        'email' => $factura->email
                    ];

                    EnviarCorreosJob::dispatch($data);
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
                $resultado = [
                    'successMessage' => 'Facturas enviadas con exito',
                    'error' => false
                ];
            }
            DB::commit();
        } catch (Exception $e) {
            $resultado = ['errorMessage' => $e->getMessage(), 'error' => true];
            Log::error('FacturaController@enviar_facturas, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
            DB::rollback();
            return $resultado;
        }
        return $resultado;
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

        return redirect()->route('facturas.iniciar');
    }
}
