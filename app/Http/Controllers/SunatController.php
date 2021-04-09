<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use DateTime;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Charge;
use Greenter\Model\Sale\FormaPagos\FormaPagoContado;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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

        $query = Comprobante::with('detalles')->where('codigo', 'like', '%' . $request->filter . '%');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
        //return $request;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexBoleta(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = Comprobante::with('detalles')->where('codigo', 'like', '%' . $request->filter . '%');

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
    public function enviarFactura(Comprobante $comprobante)
    {
        $comprobante = Comprobante::with('detalles')->where('id', 'like', $comprobante->id)->first();

        try {
            $see = require __DIR__ . '/config.php';

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

            $invoice = new Invoice();

            //Variables Globales
            $montoValorVenta = 0.0;
            $montoPrecioUnitario = 0.0;
            $subTotal = 0.0;

            $detalle = $comprobante->detalles;
            foreach ($detalle as $index => $value) {
                $montoPrecioUnitario = $value['valor_unitario'] + 18.00 / $value['cantidad'];
                $montoValorVenta = $value['cantidad'] * $value['valor_unitario'];
                $item = (new SaleDetail())
                    ->setCodProducto($value['codigo'])
                    ->setUnidad('NIU') // Unidad - Catalog. 03
                    ->setCantidad($value['cantidad'])
                    ->setMtoValorUnitario($value['valor_unitario'])
                    /*->setDescuentos([
                        (new Charge())
                            ->setCodTipo('00') // Catalog. 53
                            ->setMontoBase(200)
                            ->setFactor(0.10)
                            ->setMonto(20)
                    ])*/
                    ->setDescripcion('PRODUCTO 1')
                    ->setMtoBaseIgv(100)
                    ->setPorcentajeIgv(18.00) // 18%
                    ->setIgv(18.00)
                    ->setTipAfeIgv('10') // Gravado Op. Onerosa - Catalog. 07
                    ->setTotalImpuestos(18.00) // Suma de impuestos en el detalle
                    ->setMtoValorVenta($montoValorVenta)
                    ->setMtoPrecioUnitario($montoPrecioUnitario);

                $legend = (new Legend())
                    ->setCode('1000') // Monto en letras - Catalog. 52
                    ->setValue('SON DOSCIENTOS TREINTA Y SEIS CON 00/100 SOLES');

                $invoice->setDetails([$item])
                    ->setLegends([$legend]);

                $subTotal += $montoPrecioUnitario;
            }
            // Venta
            $invoice
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc('01') // Factura - Catalog. 01
                ->setSerie($comprobante->serie)
                ->setCorrelativo($comprobante->correlativo)
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
                ->setMtoImpVenta($comprobante->total);

            $result = $see->send($invoice);

            // Guardar XML firmado digitalmente.
            $xmlGuardado = file_put_contents(
                'public/facturas/xml' . $invoice->getName() . '.xml',
                $see->getFactory()->getLastXml()
            );

            if ($xmlGuardado) {
                $comprobante->url_xml = 'public/facturas/xml' . $invoice->getName() . '.xml';
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
                $comprobante->url_cdr = 'R-' . $invoice->getName() . '.zip';
            }


            $cdr = $result->getCdrResponse();

            $code = (int)$cdr->getCode();

            if ($code === 0) {
                $comprobante->estado = 'aceptado';
                $comprobante->observaciones =  substr($cdr->getDescription(), 0, 255);
                $comprobante->update();

                if (count($cdr->getNotes()) > 0) {
                    $comprobante->estado = 'observado';
                    $comprobante->observaciones = substr($cdr->getNotes(), 0, 255);
                    $comprobante->update();
                }
            } else if ($code >= 2000 && $code <= 3999) {
                $comprobante->estado = 'rechazado';
                $comprobante->observaciones = 'No se pudo enviar el comprobante a sunat';
                $comprobante->update();
            } else {
                $comprobante->estado = 'rechazado';
                $comprobante->observaciones = 'No se pudo enviar el comprobante a sunat';
                $comprobante->update();
            }
        } catch (\Exception $e) {
            $comprobante->observaciones = 'Error al enviar el comprobante';
            Log::error('SunatController@enviarFactura, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciarFacturas');
    }
    public function enviarBoleta(Comprobante $comprobante)
    {
        $comprobante = Comprobante::with('detalles')->where('id', 'like', $comprobante->id)->first();

        try {
            $see = require __DIR__ . '/config.php';

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


            //Variables Globales
            $valorVenta = 0.0;
            $totalImpuestos = 0.0;

            $detalle = $comprobante->detalles;
            foreach ($detalle as $index => $value) {

                if ($index <= count($detalle)) {
                    $item = (new SaleDetail())
                        ->setCodProducto($value['codigo'])
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
                $valorVenta = $valorVenta + $value['cantidad'] * $value['valor_unitario'] - $value['descuento'];
            }
            // Venta
            $invoice = (new Invoice())
                ->setUblVersion('2.1')
                ->setTipoOperacion('0101') // Venta - Catalog. 51
                ->setTipoDoc('03') // Boleta - Catalog. 01
                ->setSerie($comprobante->serie)
                ->setCorrelativo($comprobante->correlativo)
                ->setFechaEmision(new DateTime('2020-08-24 13:05:00-05:00')) // Zona horaria: Lima
                ->setFormaPago(new FormaPagoContado()) // FormaPago: Contado
                ->setTipoMoneda('PEN') // Sol - Catalog. 02
                ->setCompany($company)
                ->setClient($client)
                ->setMtoOperGravadas(100.00)
                ->setMtoIGV(18.00)
                ->setTotalImpuestos(18.00)
                ->setValorVenta($valorVenta)
                ->setSubTotal(118.00)
                ->setMtoImpVenta($comprobante->total);


            $result = $see->send($invoice);

            // Guardar XML firmado digitalmente.
            file_put_contents(
                $invoice->getName() . '.xml',
                $see->getFactory()->getLastXml()
            );

            // Verificamos que la conexión con SUNAT fue exitosa.
            if (!$result->isSuccess()) {
                // Mostrar error al conectarse a SUNAT.
                echo 'Codigo Error: ' . $result->getError()->getCode();
                echo 'Mensaje Error: ' . $result->getError()->getMessage();
                exit();
            }

            // Guardamos el CDR
            file_put_contents('R-' . $invoice->getName() . '.zip', $result->getCdrZip());

            $cdr = $result->getCdrResponse();

            $code = (int)$cdr->getCode();

            if ($code === 0) {
                $comprobante->estado = 'aceptado';
                $comprobante->observaciones =  substr($cdr->getDescription(), 0, 255);
                $comprobante->update();

                if (count($cdr->getNotes()) > 0) {
                    $comprobante->estado = 'observado';
                    $comprobante->observaciones = substr($cdr->getNotes(), 0, 255);
                    $comprobante->update();
                }
            } else if ($code >= 2000 && $code <= 3999) {
                $comprobante->estado = 'rechazado';
                $comprobante->observaciones = 'No se pudo enviar el comprobante a sunat';
                $comprobante->update();
            } else {
                $comprobante->estado = 'rechazado';
                $comprobante->observaciones = 'No se pudo enviar el comprobante a sunat';
                $comprobante->update();
            }
        } catch (\Exception $e) {
            $comprobante->observaciones = 'Error al enviar el comprobante';
            Log::error('SunatController@enviar, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }
        return redirect()->route('sunat.iniciarBoletas');
    }
    public function anularFactura(Comprobante $comprobante)
    {
        try {
            $comprobante->estado = 'anulado';
            $comprobante->update();
        } catch (\Exception $e) {
            Log::error('SunatController@anularFactura, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciarFacturas');
    }
    public function anularBoleta(Comprobante $comprobante)
    {
        try {
            $comprobante->estado = 'anulado';
            $comprobante->update();
        } catch (\Exception $e) {
            Log::error('SunatController@anularBoleta, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciarBoletas');
    }

    public function descargarArchivo(Comprobante $comprobante)
    {
        $pathtoFile = public_path() . $comprobante->url_xml;
        return response()->download($pathtoFile);
    }
}
