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
            $see = require __DIR__ . '/Sunat/config.php';

            // Cliente
            $client = (new Client())
                ->setTipoDoc('6')
                ->setNumDoc('20000000001')
                ->setRznSocial('EMPRESA X');

            // Emisor
            $address = (new Address())
                ->setUbigueo('150101')
                ->setDepartamento('LIMA')
                ->setProvincia('LIMA')
                ->setDistrito('LIMA')
                ->setUrbanizacion('-')
                ->setDireccion('Av. Villa Nueva 221')
                ->setCodLocal('0000'); // Codigo de establecimiento asignado por SUNAT, 0000 por defecto.

            $company = (new Company())
                ->setRuc('20123456789')
                ->setRazonSocial('GREEN SAC')
                ->setNombreComercial('GREEN')
                ->setAddress($address);

            // Venta
            $invoice = new Invoice();

            $detalle = $factura->detalles;
            foreach ($detalle as $index => $value) {
                $item = (new SaleDetail())
                    ->setCodProducto($value['concepto_id'])
                    ->setUnidad('NIU') // Unidad - Catalog. 03
                    ->setCantidad($value['cantidad'])
                    ->setMtoValorUnitario($value['valor_unitario'])
                    ->setDescripcion('PRODUCTO 1')
                    ->setMtoBaseIgv(100)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv(18.00)
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                    ->setMtoValorVenta(100.00)
                    ->setMtoPrecioUnitario(59.00);

                $legend = (new Legend())
                    ->setCode('1000') // Monto en letras - Catalog. 52
                    ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

                $invoice->setDetails([$item])
                    ->setLegends([$legend]);
            }
            return var_dump($item);

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
                ->setMtoImpVenta($factura->total);



            //$invoice = new Invoice();

            //Variables Globales
            //$montoValorVenta = 0.0;
            //$montoPrecioUnitario = 0.0;
            //$subTotal = 0.0;

            /*
            // Venta
            $invoice
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc('01') // Factura - Catalog. 01
                ->setSerie($factura->serie)
                ->setCorrelativo($factura->correlativo)
                ->setFechaEmision(new DateTime()) // Zona horaria: Lima
                ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
                ->setTipoMoneda('PEN') // Sol - Catalog. 02
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas(100.00)
                ->setMtoIGV(18.00)
                ->setTotalImpuestos(18.00)
                ->setValorVenta($montoValorVenta)
                ->setSubTotal($subTotal)
                ->setMtoImpVenta($factura->total);*/

            $result = $see->send($invoice);

            // Guardar XML firmado digitalmente.
            $xmlGuardado = file_put_contents(
                $invoice->getName() . '.xml',
                $see->getFactory()->getLastXml()
            );

            if ($xmlGuardado) {
                $factura->url_xml = $invoice->getName() . '.xml';
            }

            // Verificamos que la conexión con SUNAT fue exitosa.
            if (!$result->isSuccess()) {
                // Mostrar error al conectarse a SUNAT.
                echo 'Codigo Error: ' . $result->getError()->getCode();
                echo 'Mensaje Error: ' . $result->getError()->getMessage();
                exit();
            }

            // Guardamos el CDR
            $cdrGuardado = file_put_contents('R-' . $invoice->getName() . '.zip', $result->getCdrZip());
            if ($cdrGuardado) {
                $factura->url_cdr = 'R-' . $invoice->getName() . '.zip';
            }


            $cdr = $result->getCdrResponse();

            $code = (int)$cdr->getCode();

            if ($code === 0) {
                $factura->estado = 'aceptado';
                $factura->observaciones = '';
                if (count($cdr->getNotes()) > 0) {
                    $factura->estado = 'observado';
                    // Corregir estas observaciones en siguientes emisiones.
                    $factura->observaciones = var_dump($cdr->getNotes());
                }
            } else if ($code >= 2000 && $code <= 3999) {
                $factura->estado = 'rechazado';
                //echo 'ESTADO: RECHAZADA' . PHP_EOL;
            } else {
                /* Esto no debería darse, pero si ocurre, es un CDR inválido que debería tratarse como un error-excepción. */
                /*code: 0100 a 1999 */
                $factura->estado = 'rechazado';
            }

            $factura->observaciones =  $factura->observaciones . $cdr->getDescription() . PHP_EOL;

            $html = new HtmlReport();
            $html->setTemplate('/Sunat/invoice.html.twig');

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
                    'logo' => file_get_contents(__DIR__ . '/Sunat/siscaja_blanco.png'), // Logo de Empresa
                    'hash' => 'qqnr2dN4p/HmaEA/CJuVGo7dv5g=', // Valor Resumen
                ],
                'user' => [
                    'header'     => 'Telf: <b>(01) 123375</b>', // Texto que se ubica debajo de la dirección de empresa
                    'extras'     => [
                        // Leyendas adicionales
                        ['name' => 'CONDICION DE PAGO', 'value' => 'Efectivo'],
                        ['name' => 'VENDEDOR', 'value' => 'GITHUB SELLER'],
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
            }
            $factura->update();
        } catch (\Exception $e) {
            $factura->estado = 'noEnviado';
            $factura->observaciones = 'Error al enviar la factura' . $e;
            $factura->update();
            Log::error('SunatController@enviarFactura, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciarFacturas');
        //return $factura;
    }
    public function enviarBoleta(Comprobante $boleta)
    {
    }
    public function anularFactura(Comprobante $factura)
    {
        try {
            $factura->estado = 'anulado';
            $factura->update();
        } catch (\Exception $e) {
            Log::error('SunatController@anularFactura, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciarFacturas');
    }
    public function anularBoleta(Comprobante $boleta)
    {
        try {
            $boleta->estado = 'anulado';
            $boleta->update();
        } catch (\Exception $e) {
            Log::error('SunatController@anularBoleta, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciarBoletas');
    }
}
