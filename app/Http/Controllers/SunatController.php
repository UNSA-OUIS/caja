<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Concepto;
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

//require '../vendor/autoload.php';

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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
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
    public function enviar(Comprobante $comprobante)
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

            // Venta
            $serie = substr($comprobante->serie, 0, 1);
            if ($serie == 'F') {
                $invoice = (new Invoice())
                    ->setUblVersion('2.1')
                    ->setTipoOperacion('0101') // Venta - Catalog. 51
                    ->setTipoDoc('01') // Factura - Catalog. 01
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
                    ->setValorVenta(100.00)
                    ->setSubTotal(118.00)
                    ->setMtoImpVenta($comprobante->total);
            } elseif ($serie == 'B') {
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
                    ->setValorVenta(100.00)
                    ->setSubTotal(118.00)
                    ->setMtoImpVenta($comprobante->total);
            }
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
            }


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
                $comprobante->update();
                $resultado = ['successMessage' => 'Comprobante enviado a sunat con éxito' . $cdr->getDescription() . PHP_EOL];

                if (count($cdr->getNotes()) > 0) {
                    $comprobante->estado = 'observado';
                    $comprobante->update();
                    $resultado = ['warningMessage' => 'OBSERVACIONES:' . PHP_EOL . var_dump($cdr->getNotes())];
                }
            } else if ($code >= 2000 && $code <= 3999) {
                $comprobante->estado = 'rechazado';
                $comprobante->update();
                $resultado = ['errorMessage' => 'No se pudo enviar el comprobante a sunat'];
            } else {
                $comprobante->estado = 'rechazado';
                $comprobante->update();
                $resultado = ['errorMessage' => 'No se pudo enviar el comprobante a sunat'];
            }
        } catch (\Exception $e) {
            $resultado = ['errorMessage' => 'Error al enviar el comprobante' . $e];
            Log::error('SunatController@enviar, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }
        //return redirect()->route('sunat.iniciar')->with($resultado);
        return $resultado;
    }
    public function anular(Comprobante $comprobante)
    {
        try {
            $comprobante->estado = 'anulado';
            $comprobante->update();
            $result = ['successMessage' => 'Comprobante anulado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo anular el comprobante'];
            Log::error('SunatController@anular, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('sunat.iniciar')->with($result);
    }
}
