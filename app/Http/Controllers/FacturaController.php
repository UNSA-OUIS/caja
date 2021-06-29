<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarCorreosJob;
use App\Models\Comprobante;
use DateTime;
use Exception;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Detraction;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\Note;
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

                /**Notas de debito relacionadas a facturas */
                $notas_debito = Comprobante::with('comprobanteable')->with('tipo_comprobante')
                    ->with('detalles.concepto')->where('comprobante_afectado_id', $factura->id)->where('tipo_comprobante_id', 3)->where('estado', 'no_enviado')->get();
                if ($notas_debito) {
                    foreach ($notas_debito as $index => $value) {
                        $nota_debito = new Comprobante();
                        $nota_debito = Comprobante::with('comprobanteable')->with('tipo_comprobante')
                            ->with('detalles.concepto')->where('id', $value['id'])->first();

                        $note = new Note();
                        $note
                            ->setUblVersion('2.1')
                            ->setTipDocAfectado('01')
                            ->setNumDocfectado($factura->serie . '-' . $factura->correlativo)
                            ->setCodMotivo($nota_debito->tipo_nota)
                            ->setDesMotivo($nota_debito->motivo)
                            ->setTipoDoc('08')
                            ->setSerie($nota_debito->serie)
                            ->setFechaEmision(new DateTime())
                            ->setCorrelativo($nota_debito->correlativo)
                            ->setTipoMoneda('PEN')
                            ->setCompany($this->empresa)
                            ->setClient($client)
                            ->setMtoOperGravadas(200)
                            ->setMtoIGV(36)
                            ->setTotalImpuestos(36)
                            ->setMtoImpVenta(236);

                        $detalles = $nota_debito->detalles;

                        foreach ($detalles as $index => $value) {
                            $items[$index] = (new SaleDetail())
                                ->setCodProducto($detalles[$index]->concepto['id'])
                                ->setUnidad('NIU') // Unidad - Catalog. 03
                                ->setCantidad($value['cantidad'])
                                ->setDescripcion($detalles[$index]->concepto['descripcion'])
                                ->setMtoValorUnitario(50.00)
                                ->setMtoBaseIgv(100)
                                ->setPorcentajeIgv(18.00) // 18%
                                ->setIgv(18.00)
                                ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                                ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                                ->setMtoValorVenta(100.00)
                                ->setMtoPrecioUnitario(59.00);
                        }


                        $formatter = new NumeroALetras();
                        $montoLetras = $formatter->toInvoice($factura->total, 2, 'soles');

                        $legend = (new Legend())
                            ->setCode('1000') // Monto en letras - Catalog. 52
                            ->setValue($montoLetras);

                        $note->setDetails($items)
                            ->setLegends([$legend]);

                        $result = $see->send($note);

                        // Guardar XML firmado digitalmente.
                        $xmlGuardado = file_put_contents(
                            storage_path('app/public/Sunat/XML/' . $nota_debito->serie . '-' . $nota_debito->correlativo . '.xml'),
                            $see->getFactory()->getLastXml()
                        );

                        if ($xmlGuardado) {
                            $nota_debito->url_xml = $nota_debito->serie . '-' . $nota_debito->correlativo . '.xml';
                            $nota_debito->update();
                        } else {
                            $resultado = ['errorMessage' => 'No se pudo generar el xml de la nota de debito' . $nota_debito->serie . '-' . $nota_debito->correlativo, 'error' => true];
                            DB::rollback();
                            return $resultado;
                        }

                        // Verificamos que la conexión con SUNAT fue exitosa.
                        if (!$result->isSuccess()) {
                            // Mostrar error al conectarse a SUNAT.
                            $resultado = ['errorMessage' => var_dump($result->getError()), 'error' => true];
                            DB::rollback();
                            return $resultado;
                        }

                        // Guardamos el CDR
                        $cdrGuardado = file_put_contents(storage_path('app/public/Sunat/CDR/' . 'R-' . $nota_debito->serie . '-' . $nota_debito->correlativo . '.zip'), $result->getCdrZip());
                        if ($cdrGuardado) {
                            $nota_debito->url_cdr = 'R-' . $nota_debito->serie . '-' . $nota_debito->correlativo . '.zip';
                            $nota_debito->update();
                        } else {
                            $resultado = ['errorMessage' => 'No se pudo generar el cdr de la nota de debito' . $nota_debito->serie . '-' . $nota_debito->correlativo, 'error' => true];
                            DB::rollback();
                            return $resultado;
                        }

                        $cdr = $result->getCdrResponse();

                        $code = (int)$cdr->getCode();

                        if ($code === 0) {
                            $nota_debito->estado = 'aceptado';
                            $nota_debito->observaciones = $cdr->getDescription() . PHP_EOL;
                            $nota_debito->update();
                            if (count($cdr->getNotes()) > 0) {
                                // Corregir estas observaciones en siguientes emisiones.
                                $nota_debito->estado = 'observado';
                                $nota_debito->observaciones = '';
                                $nota_debito->update();
                                foreach ($cdr->getNotes() as $index => $value) {
                                    $nota_debito->observaciones .= json_encode('Observacion #' . $index . '=>' . $value, JSON_UNESCAPED_UNICODE) . "<br>";
                                }
                                $nota_debito->update();
                            }
                        } else if ($code >= 2000 && $code <= 3999) {
                            $nota_debito->estado = 'rechazado';
                            $nota_debito->observaciones = '';
                            $nota_debito->update();
                        } else {
                            /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
                            /*code: 0100 a 1999 */
                            $nota_debito->estado = 'rechazado';
                            $nota_debito->observaciones = '';
                            $nota_debito->update();
                        }
                    }
                }

                /**Notas de credito relacionadas a facturas */
                $notas_credito = Comprobante::with('comprobanteable')->with('tipo_comprobante')
                    ->with('detalles.concepto')->where('comprobante_afectado_id', $factura->id)->where('tipo_comprobante_id', 4)->where('estado', 'no_enviado')->get();
                if ($notas_credito) {
                    foreach ($notas_credito as $index => $value) {
                        $nota_credito = new Comprobante();
                        $nota_credito = Comprobante::with('comprobanteable')->with('tipo_comprobante')
                            ->with('detalles.concepto')->where('id', $value['id'])->first();

                        $note = new Note();
                        $note
                            ->setUblVersion('2.1')
                            ->setTipDocAfectado('01')
                            ->setNumDocfectado($factura->serie . '-' . $factura->correlativo)
                            ->setCodMotivo($nota_credito->tipo_nota)
                            ->setDesMotivo($nota_credito->motivo)
                            ->setTipoDoc('07')
                            ->setSerie($nota_credito->serie)
                            ->setFechaEmision(new DateTime())
                            ->setCorrelativo($nota_credito->correlativo)
                            ->setTipoMoneda('PEN')
                            ->setCompany($this->empresa)
                            ->setClient($client)
                            ->setMtoOperGravadas(200)
                            ->setMtoIGV(36)
                            ->setTotalImpuestos(36)
                            ->setMtoImpVenta(236);

                        $detalles = $nota_credito->detalles;

                        foreach ($detalles as $index => $value) {
                            $items[$index] = (new SaleDetail())
                                ->setCodProducto($detalles[$index]->concepto['id'])
                                ->setUnidad('NIU') // Unidad - Catalog. 03
                                ->setCantidad($value['cantidad'])
                                ->setDescripcion($detalles[$index]->concepto['descripcion'])
                                ->setMtoValorUnitario(50.00)
                                ->setMtoBaseIgv(100)
                                ->setPorcentajeIgv(18.00) // 18%
                                ->setIgv(18.00)
                                ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                                ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                                ->setMtoValorVenta(100.00)
                                ->setMtoPrecioUnitario(59.00);
                        }


                        $formatter = new NumeroALetras();
                        $montoLetras = $formatter->toInvoice($factura->total, 2, 'soles');

                        $legend = (new Legend())
                            ->setCode('1000') // Monto en letras - Catalog. 52
                            ->setValue($montoLetras);

                        $note->setDetails($items)
                            ->setLegends([$legend]);

                        $result = $see->send($note);

                        // Guardar XML firmado digitalmente.
                        $xmlGuardado = file_put_contents(
                            storage_path('app/public/Sunat/XML/' . $nota_credito->serie . '-' . $nota_credito->correlativo . '.xml'),
                            $see->getFactory()->getLastXml()
                        );

                        if ($xmlGuardado) {
                            $nota_credito->url_xml = $nota_credito->serie . '-' . $nota_credito->correlativo . '.xml';
                            $nota_credito->update();
                        } else {
                            $resultado = ['errorMessage' => 'No se pudo generar el xml de la nota de debito' . $nota_credito->serie . '-' . $nota_credito->correlativo, 'error' => true];
                            DB::rollback();
                            return $resultado;
                        }

                        // Verificamos que la conexión con SUNAT fue exitosa.
                        if (!$result->isSuccess()) {
                            // Mostrar error al conectarse a SUNAT.
                            $resultado = ['errorMessage' => var_dump($result->getError()), 'error' => true];
                            DB::rollback();
                            return $resultado;
                        }

                        // Guardamos el CDR
                        $cdrGuardado = file_put_contents(storage_path('app/public/Sunat/CDR/' . 'R-' . $nota_credito->serie . '-' . $nota_credito->correlativo . '.zip'), $result->getCdrZip());
                        if ($cdrGuardado) {
                            $nota_credito->url_cdr = 'R-' . $nota_credito->serie . '-' . $nota_credito->correlativo . '.zip';
                            $nota_credito->update();
                        } else {
                            $resultado = ['errorMessage' => 'No se pudo generar el cdr de la nota de debito' . $nota_credito->serie . '-' . $nota_credito->correlativo, 'error' => true];
                            DB::rollback();
                            return $resultado;
                        }

                        $cdr = $result->getCdrResponse();

                        $code = (int)$cdr->getCode();

                        if ($code === 0) {
                            $nota_credito->estado = 'aceptado';
                            $nota_credito->observaciones = $cdr->getDescription() . PHP_EOL;
                            $nota_credito->update();
                            if (count($cdr->getNotes()) > 0) {
                                // Corregir estas observaciones en siguientes emisiones.
                                $nota_credito->estado = 'observado';
                                $nota_credito->observaciones = '';
                                $nota_credito->update();
                                foreach ($cdr->getNotes() as $index => $value) {
                                    $nota_credito->observaciones .= json_encode('Observacion #' . $index . '=>' . $value, JSON_UNESCAPED_UNICODE) . "<br>";
                                }
                                $nota_credito->update();
                            }
                        } else if ($code >= 2000 && $code <= 3999) {
                            $nota_credito->estado = 'rechazado';
                            $nota_credito->observaciones = '';
                            $nota_credito->update();
                        } else {
                            /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
                            /*code: 0100 a 1999 */
                            $nota_credito->estado = 'rechazado';
                            $nota_credito->observaciones = '';
                            $nota_credito->update();
                        }
                    }
                }

                $invoice = new Invoice();

                $detalle = $factura->detalles;
                foreach ($detalle as $index => $value) {
                    $items[$index] = (new SaleDetail())
                        ->setCodProducto($detalle[$index]->concepto['id'])
                        ->setUnidad('NIU') // Unidad - Catalog. 03
                        ->setCantidad($value['cantidad'])
                        ->setDescripcion($detalle[$index]->concepto['descripcion'])
                        ->setMtoValorUnitario(50.00)
                        ->setMtoBaseIgv(100)
                        ->setPorcentajeIgv(18.00) // 18%
                        ->setIgv(18.00)
                        ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                        ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                        ->setMtoValorVenta(100.00)
                        ->setMtoPrecioUnitario(59.00);
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
                    ->setMtoOperGravadas(100.00)
                    ->setMtoIGV(18.00)
                    ->setTotalImpuestos(18.00)
                    ->setValorVenta(100.00)
                    ->setSubTotal(118.00)
                    ->setMtoImpVenta(118.00)
                    ->setDetraccion(
                        // MONEDA SIEMPRE EN SOLES
                        (new Detraction())
                            // Carnes y despojos comestibles
                            ->setCodBienDetraccion('014') // catalog. 54
                            // Deposito en cuenta
                            ->setCodMedioPago('001') // catalog. 59
                            ->setCtaBanco('0004-3342343243')
                            ->setPercent(4.00)
                            ->setMount(37.76)
                    );


                $result = $see->send($invoice);

                // Guardar XML firmado digitalmente.
                $xmlGuardado = file_put_contents(
                    storage_path('app/public/Sunat/XML/' . $factura->serie . '-' . $factura->correlativo . '.xml'),
                    $see->getFactory()->getLastXml()
                );

                if ($xmlGuardado) {
                    $factura->url_xml = $factura->serie . '-' . $factura->correlativo . '.xml';
                    $factura->update();
                } else {
                    $resultado = ['errorMessage' => 'No se pudo generar el xml de la factura' . $factura->serie . $factura->correlativo, 'error' => true];
                    DB::rollback();
                    return $resultado;
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
                } else {
                    $resultado = ['errorMessage' => 'No se pudo generar el cdr de la factura' . $factura->serie . $factura->correlativo, 'error' => true];
                    DB::rollback();
                    return $resultado;
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
            }
            $resultado = ['successMessage' => 'Facturas enviadas con exito', 'error' => false];
            DB::commit();
            return $resultado;
        } catch (Exception $e) {
            $resultado = ['errorMessage' => $e->getMessage(), 'error' => true];
            Log::error($e . 'FacturaController@enviar_facturas, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
            DB::rollback();
            return $resultado;
        }
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
