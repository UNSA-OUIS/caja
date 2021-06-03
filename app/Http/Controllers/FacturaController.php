<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarCorreosJob;
use App\Models\Comprobante;
use App\Models\Concepto;
use DateTime;
use Exception;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Detraction;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;
use Luecano\NumeroALetras\NumeroALetras;

class FacturaController extends Controller
{
    private $empresa;
    private $error = '';

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
            ->where('tipo_usuario', 'empresa')
            ->where('tipo_comprobante_id', 2)
            ->where('enviado', false)
            ->whereDate('created_at', '>=', $request->fecha_inicio)
            ->whereDate('created_at', '<=', $request->fecha_fin);
            //->where('cajero_id', 'like', Auth::user()->id);

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }
    public function enviar_facturas(Request $request)
    {
        $see = require config_path('Sunat\config.php');
        $facturas = $request->all();

        foreach ($facturas as $index => $value) {
            try {
                $factura = new Comprobante();

                $factura = Comprobante::with('comprobanteable')->with('tipo_comprobante')
                    ->with('detalles.concepto')->where('id', 'like', $value['id'])->first();

                // Cliente
                $client = (new Client())
                    ->setTipoDoc('6')
                    ->setNumDoc($factura->comprobanteable->ruc)
                    ->setRznSocial($factura->comprobanteable->razon_social);

                // Venta
                $invoice = (new Invoice())
                    ->setUblVersion('2.1')
                    ->setTipoOperacion('0101') // Venta - Catalog. 51
                    ->setTipoDoc('01') // Factura - Catalog. 01
                    ->setSerie($factura->serie)
                    ->setCorrelativo($factura->correlativo)
                    ->setFechaEmision(new DateTime('2020-08-24 13:05:00-05:00')) // Zona horaria: Lima
                    ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
                    ->setTipoMoneda('PEN') // Sol - Catalog. 02
                    ->setCompany($this->empresa)
                    ->setClient($client)
                    ->setMtoOperGravadas(100.00)
                    ->setMtoIGV(18.00)
                    ->setTotalImpuestos(18.00)
                    ->setValorVenta(100.00)
                    ->setSubTotal(118.00)
                    ->setMtoImpVenta(118.00);

                $detalle = $factura->detalles;
                foreach ($detalle as $index => $value) {
                    $concepto = Concepto::with('tipo_concepto')
                        ->with('clasificador')
                        ->with('unidad_medida')
                        ->where('id', 'like', $factura->detalles[$index]->concepto_id)->first();
                    $items[$index] = (new SaleDetail())
                        ->setCodProducto($concepto->id)
                        ->setUnidad('NIU') // Unidad - Catalog. 03
                        ->setCantidad(2)
                        ->setMtoValorUnitario(50.00)
                        ->setDescripcion($concepto->descripcion)
                        ->setMtoBaseIgv(100)
                        ->setPorcentajeIgv(18.00) // 18%
                        ->setIgv(18.00)
                        ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                        ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                        ->setMtoValorVenta(100.00)
                        ->setMtoPrecioUnitario(59.00);
                    /*if ($concepto->tipo_afectacion == 10) {
                        $total_gravadas += $value['valor_unitario'] * $value['cantidad'];
                    } elseif ($concepto->tipo_afectacion == 20) {
                        $total_exonerados += $value['valor_unitario'] * $value['cantidad'];
                    } elseif ($concepto->tipo_afectacion == 30) {
                        $total_inafectos += $value['valor_unitario'] * $value['cantidad'];
                    }
                    $igv += 18.00 / $value['cantidad'];*/
                }

                $legend = (new Legend())
                    ->setCode('1000') // Monto en letras - Catalog. 52
                    ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

                $invoice->setDetails($items)
                    ->setLegends([$legend]);
                /*$invoice = new Invoice();

                $total_gravadas = 0;
                $total_exonerados = 0;
                $total_inafectos = 0;
                $subtotal = 0;
                $igv = 0;

                $detalle = $factura->detalles;
                foreach ($detalle as $index => $value) {
                    $concepto = Concepto::with('tipo_concepto')
                        ->with('clasificador')
                        ->with('unidad_medida')
                        ->where('id', 'like', $factura->detalles[$index]->concepto_id)->first();
                    $items[$index] = (new SaleDetail())
                        ->setCodProducto($concepto->id)
                        ->setUnidad('NIU') // Unidad - Catalog. 03
                        ->setCantidad($value['cantidad'])
                        ->setMtoValorUnitario($value['valor_unitario'])
                        ->setDescripcion($concepto->descripcion)
                        ->setMtoBaseIgv(100.00)
                        ->setPorcentajeIgv(18.00) // 18%
                        ->setIgv(18.00 / $value['cantidad'])
                        ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                        ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                        ->setMtoValorVenta($value['valor_unitario'] * $value['cantidad'])
                        ->setMtoPrecioUnitario($value['valor_unitario'] + 18.00 / $value['cantidad']);
                    if ($concepto->tipo_afectacion == 10) {
                        $total_gravadas += $value['valor_unitario'] * $value['cantidad'];
                    } elseif ($concepto->tipo_afectacion == 20) {
                        $total_exonerados += $value['valor_unitario'] * $value['cantidad'];
                    } elseif ($concepto->tipo_afectacion == 30) {
                        $total_inafectos += $value['valor_unitario'] * $value['cantidad'];
                    }
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
                    ->setMtoIGV($igv)
                    ->setTotalImpuestos($igv);
                if ($total_gravadas) {
                    $invoice
                        ->setValorVenta($total_gravadas)
                        ->setSubTotal($total_gravadas + $igv)
                        ->setMtoImpVenta($total_gravadas + $igv)
                        ->setMtoOperGravadas($total_agravadas);
                } else if ($total_exonerados) {
                    $invoice
                        ->setValorVenta($total_exonerados)
                        ->setSubTotal($total_exonerados + $igv)
                        ->setMtoImpVenta($total_exonerados + $igv)
                        ->setMtoOperGravadas($total_exonerados);
                } else if ($total_inafectos) {
                    $invoice
                        ->setValorVenta($total_inafectos)
                        ->setSubTotal($total_inafectos + $igv)
                        ->setMtoImpVenta($total_inafectos + $igv)
                        ->setMtoOperGravadas($total_inafectos);
                }

                if ($concepto->detraccion) {
                    $invoice->setDetraccion(
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
                }*/



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
                    $factura->observaciones = 'Codigo Error: ' . $result->getError()->getCode() . '\n' . 'Mensaje Error: ' . $result->getError()->getMessage();
                    $factura->update();
                    return $factura;
                    exit();
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
                            $factura->observaciones .= json_encode('Observacion #' . $index . '=>' . $value, JSON_UNESCAPED_UNICODE) . "\n";
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
            } catch (Exception $e) {
                $factura->observaciones = 'Error al enviar la factura' . $e->getMessage();
                $factura->update();
                $resultado = ['errorMessage' => $e->getMessage(), 'error' => true];
                Log::error('FacturaController@enviar_facturas, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
            }
        }
        return $resultado;
    }
    public function enviar(Comprobante $factura)
    {
        $factura = Comprobante::with('comprobanteable')->with('tipo_comprobante')
            ->with('detalles')->where('id', 'like', $factura->id)->first();

        try {
            $see = require config_path('Sunat\config.php');

            // Cliente
            $client = (new Client())
                ->setTipoDoc('6')
                ->setNumDoc($factura->comprobanteable->ruc)
                ->setRznSocial($factura->comprobanteable->razon_social);

            // Venta
            $invoice = new Invoice();

            $total_gravadas = 0;
            $total_exonerados = 0;
            $total_inafectos = 0;
            $subtotal = 0;
            $igv = 0;

            $detalle = $factura->detalles;
            foreach ($detalle as $index => $value) {
                $concepto = Concepto::with('tipo_concepto')
                    ->with('clasificador')
                    ->with('unidad_medida')
                    ->where('id', 'like', $factura->detalles[$index]->concepto_id)->first();
                $items[$index] = (new SaleDetail())
                    ->setCodProducto($concepto->id)
                    ->setUnidad('NIU') // Unidad - Catalog. 03
                    ->setCantidad($value['cantidad'])
                    ->setMtoValorUnitario($value['valor_unitario'])
                    ->setDescripcion($concepto->descripcion)
                    ->setMtoBaseIgv(100.00)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv(18.00 / $value['cantidad'])
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                    ->setMtoValorVenta($value['valor_unitario'] * $value['cantidad'])
                    ->setMtoPrecioUnitario($value['valor_unitario'] + 18.00 / $value['cantidad']);
                if ($concepto->tipo_afectacion == 10) {
                    $total_gravadas += $value['valor_unitario'] * $value['cantidad'];
                } elseif ($concepto->tipo_afectacion == 20) {
                    $total_exonerados += $value['valor_unitario'] * $value['cantidad'];
                } elseif ($concepto->tipo_afectacion == 30) {
                    $total_inafectos += $value['valor_unitario'] * $value['cantidad'];
                }
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
                ->setMtoIGV($igv)
                ->setTotalImpuestos($igv);
            if ($total_gravadas) {
                $invoice
                    ->setValorVenta($total_gravadas)
                    ->setSubTotal($total_gravadas + $igv)
                    ->setMtoImpVenta($total_gravadas + $igv)
                    ->setMtoOperGravadas($total_agravadas);
            } else if ($total_exonerados) {
                $invoice
                    ->setValorVenta($total_exonerados)
                    ->setSubTotal($total_exonerados + $igv)
                    ->setMtoImpVenta($total_exonerados + $igv)
                    ->setMtoOperGravadas($total_exonerados);
            } else if ($total_inafectos) {
                $invoice
                    ->setValorVenta($total_inafectos)
                    ->setSubTotal($total_inafectos + $igv)
                    ->setMtoImpVenta($total_inafectos + $igv)
                    ->setMtoOperGravadas($total_inafectos);
            }

            if ($concepto->detraccion) {
                $invoice->setDetraccion(
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
            }



            $result = $see->send($invoice);

            // Guardar XML firmado digitalmente.
            $xmlGuardado = file_put_contents(
                storage_path('app/public/Sunat/XML/' . $invoice->getName() . '.xml'),
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
            $cdrGuardado = file_put_contents(storage_path('app/public/Sunat/CDR/' . 'R-' . $invoice->getName() . '.zip'), $result->getCdrZip());
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
        } catch (\Exception $e) {
            $factura->observaciones = 'Error al enviar la factura' . $e;
            $factura->update();
            return $e;
        }

        return redirect()->route('facturas.iniciar');
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

/*$html = new HtmlReport();
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

            $pdfGuardado = file_put_contents(storage_path('app/public/Sunat/PDF/' . $invoice->getName() . '.pdf'), $pdf);
            if ($pdfGuardado) {
                $factura->url_pdf = $invoice->getName() . '.pdf';
                $factura->update();
            }*/
