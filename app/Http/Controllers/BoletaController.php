<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\ResumenDiario;
use DateTime;
use Exception;
use Greenter\Model\Client\Client;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;
use Greenter\Model\Sale\Invoice;
use Greenter\Model\Sale\Legend;
use Greenter\Model\Sale\SaleDetail;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Greenter\Report\HtmlReport;
use Greenter\Report\PdfReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Luecano\NumeroALetras\NumeroALetras;

class BoletaController extends Controller
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

        $query = Comprobante::with('tipo_comprobante')
            ->with('detalles')->where('tipo_comprobante_id', 'like', 1);

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
    }

    public function resumenDiario(Request $filtro)
    {
        //dd($filtro->all());
        $see = require config_path('Sunat\config.php');
        $correlativo = '';

        try {

            $boletas = Comprobante::with('tipo_comprobante')
                ->with('detalles')->where('tipo_comprobante_id', 'like', 1)->get();
            $ultimo = ResumenDiario::latest('created_at')->first();
            if (!$ultimo) {
                $correlativo = '00000001';
            } else {
                $ultimo->correlativo += 1;
                $correlativo = str_pad($ultimo->correlativo, 8, "0", STR_PAD_LEFT);
            }


            foreach ($boletas as $index => $value) {
                $details[$index] = (new SummaryDetail())
                    ->setTipoDoc('03') // Boleta
                    ->setSerieNro('B00' . $index . '-' . $value['correlativo']);
                if ($value['estado'] == 'anulado') {
                    $details[$index]->setEstado('3'); // Anulación
                } else {
                    $details[$index]->setEstado('2');
                }
                if ($value['tipo_usuario'] == 'alumno') {
                    $details[$index]
                        ->setClienteTipo('1')
                        ->setClienteNro($value['codi_usuario']);
                }
                $details[$index]->setTotal($value['total'])
                    ->setMtoOperGravadas($value['total_impuesto'])
                    ->setMtoIGV(18.00);
            }

            $resumen = new Summary();
            $resumen->setFecGeneracion(new \DateTime(now())) // Fecha de emisión de las boletas.
                ->setFecResumen(new \DateTime(now())) // Fecha de envío del resumen diario.
                ->setCorrelativo('001') // Correlativo, necesario para diferenciar de otros Resumen diario del mismo día.
                ->setCompany($this->empresa)
                ->setDetails($details);

            $result = $see->send($resumen);
            // Guardar XML
            file_put_contents(
                $resumen->getName() . '.xml',
                $see->getFactory()->getLastXml()
            );

            if (!$result->isSuccess()) {
                // Si hubo error al conectarse al servicio de SUNAT.
                var_dump($result->getError());
                exit();
            }

            $ticket = $result->getTicket();
            echo 'Ticket : ' . $ticket . PHP_EOL;

            $statusResult = $see->getStatus($ticket);
            if (!$statusResult->isSuccess()) {
                // Si hubo error al conectarse al servicio de SUNAT.
                var_dump($statusResult->getError());
                return;
            }

            echo $statusResult->getCdrResponse()->getDescription();
            // Guardar CDR
            file_put_contents('R-' . $resumen->getName() . '.zip', $statusResult->getCdrZip());

            $resumen_diario = new ResumenDiario();
            $resumen_diario->fecha_envio = now();
            $resumen_diario->fecha_emision = now();
            $resumen_diario->serie = 'RC';
            $resumen_diario->correlativo = $correlativo;
            $resumen_diario->estado = 'aceptado';
            $resumen_diario->save();
        } catch (\Throwable $th) {
            echo $th;
        }

        return redirect()->route('boletas.iniciar');
    }
    public function anular(Comprobante $boleta)
    {
        try {
            $boleta->estado = 'anulado';
            $boleta->observaciones = '';
            $boleta->update();
        } catch (\Exception $e) {
            Log::error('SunatController@anularBoleta, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('boletas.iniciar');
    }
}
