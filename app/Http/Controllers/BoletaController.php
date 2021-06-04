<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarCorreosJob;
use App\Models\Alumno;
use App\Models\Comprobante;
use App\Models\Docente;
use App\Models\Particular;
use App\Models\ResumenDiario;
use Carbon\Carbon;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
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
        //$this->fechaInicio = $request->fechaInicio;

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')
            ->where('tipo_comprobante_id', 'like', 1)
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

    public function index_actual(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')
            ->where('tipo_comprobante_id', 'like', 1)
            ->where('enviado', false)
            ->whereIn('estado', ['anulado', 'observado'])
            ->where('cajero_id', 'like', Auth::user()->id)
            ->whereDate('created_at', '=', Carbon::now()->format('Y-m-d'))->get();

        /*$sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }*/

        //return $query->paginate($request->size);
        return $query;
    }


    public function resumenDiario(Request $request)
    {
        //return $request[0]['correlativo'];
        $see = require config_path('Sunat/config.php');
        $boletas = $request->all();
        $correlativo = '';

        try {

            $ultimo = ResumenDiario::latest('created_at')->first();
            if (!$ultimo) {
                $correlativo = '001';
            } else {
                $ultimo->correlativo += 1;
                $correlativo = str_pad($ultimo->correlativo, 3, "0", STR_PAD_LEFT);
            }
            //Recorro todas las boletas y las anexo al resumen diario
            foreach ($boletas as $index => $value) {
                $details[$index] = (new SummaryDetail())
                    ->setTipoDoc('03') // Boleta
                    ->setSerieNro($value['serie'] . '-' . $value['correlativo']);
                if ($value['estado'] == 'anulado') {
                    $details[$index]->setEstado('3'); // Anulación
                } else {
                    $details[$index]->setEstado('1');
                }
                if ($value['tipo_usuario'] == 'alumno') {
                    $alumno = Alumno::where('cui', $value['codi_usuario'])->first();
                    $dni = substr($alumno->dic, 1);
                    $details[$index]
                        ->setClienteTipo('1')
                        ->setClienteNro($dni);
                } elseif ($value['tipo_usuario'] == 'particular') {
                    $particular = Particular::where('dni', $value['codi_usuario'])->first();
                    $dni = $particular->dni;
                    $details[$index]
                        ->setClienteTipo('1')
                        ->setClienteNro($dni);
                } elseif ($value['tipo_usuario'] == 'docente') {
                    $docente = Docente::where('codper', $value['codi_usuario'])->first();
                    $dni = $docente->dic;
                    $details[$index]
                        ->setClienteTipo('1')
                        ->setClienteNro($dni);
                } elseif ($value['tipo_usuario'] == 'dependencia') {
                    $dni = '72351610';
                    $details[$index]
                        ->setClienteTipo('1')
                        ->setClienteNro($dni);
                }
                $details[$index]->setTotal($value['total'])
                    ->setMtoOperGravadas($value['total_gravada'])
                    ->setMtoOperInafectas($value['total_inafecta'])
                    ->setMtoIGV($value['total_impuesto']);
            }

            //Recorro todas las boletas y anexo todas la notas de debito
            foreach ($boletas as $index => $value) {
                $notas_debito = Comprobante::where('comprobante_afectado_id', $value['id'])->where('tipo_comprobante_id', 3)->get();
                //return $notas_debito[0]->serie;
                if ($notas_debito != '') {
                    $details1[$index] = (new SummaryDetail())
                        ->setTipoDoc('08') // Nota de debito
                        ->setSerieNro($notas_debito[0]->serie . '-' . $notas_debito[0]->correlativo)
                        ->setDocReferencia((new Document)
                            ->setTipoDoc(03)
                            ->setNroDoc($value['serie'] . '-' . $value['correlativo']));
                    if ($value['estado'] == 'anulado') {
                        $details1[$index]->setEstado('3'); // Anulación
                    } else {
                        $details1[$index]->setEstado('1');
                    }
                    if ($value['tipo_usuario'] == 'alumno') {
                        $alumno = Alumno::where('cui', $value['codi_usuario'])->first();
                        $dni = substr($alumno->dic, 1);
                        $details1[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    } elseif ($value['tipo_usuario'] == 'particular') {
                        $particular = Particular::where('dni', $value['codi_usuario'])->first();
                        $dni = $particular->dni;
                        $details1[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    } elseif ($value['tipo_usuario'] == 'docente') {
                        $docente = Docente::where('codper', $value['codi_usuario'])->first();
                        $dni = $docente->dic;
                        $details1[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    } elseif ($value['tipo_usuario'] == 'dependencia') {
                        $dni = '72351610';
                        $details1[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    }
                    $details1[$index]->setTotal($value['total'])
                        ->setMtoOperGravadas($value['total_gravada'])
                        ->setMtoOperInafectas($value['total_inafecta'])
                        ->setMtoIGV($value['total_impuesto']);
                }
            }

            //Recorro todas la boletas y anexo las notas de credito
            foreach ($boletas as $index => $value) {
                $notas_credito = Comprobante::where('comprobante_afectado_id', $value['id'])->where('tipo_comprobante_id', 4)->get();
                if ($notas_credito != '') {
                    $details2[$index] = (new SummaryDetail())
                        ->setTipoDoc('07') // Nota de credito
                        ->setSerieNro($notas_credito[0]->serie . '-' . $notas_credito[0]->correlativo)
                        ->setDocReferencia((new Document)
                            ->setTipoDoc(03)
                            ->setNroDoc($value['serie'] . '-' . $value['correlativo']));
                    if ($value['estado'] == 'anulado') {
                        $details2[$index]->setEstado('3'); // Anulación
                    } else {
                        $details2[$index]->setEstado('1');
                    }
                    if ($value['tipo_usuario'] == 'alumno') {
                        $alumno = Alumno::where('cui', $value['codi_usuario'])->first();
                        $dni = substr($alumno->dic, 1);
                        $details2[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    } elseif ($value['tipo_usuario'] == 'particular') {
                        $particular = Particular::where('dni', $value['codi_usuario'])->first();
                        $dni = $particular->dni;
                        $details2[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    } elseif ($value['tipo_usuario'] == 'docente') {
                        $docente = Docente::where('codper', $value['codi_usuario'])->first();
                        $dni = $docente->dic;
                        $details2[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    } elseif ($value['tipo_usuario'] == 'dependencia') {
                        $dni = '72351610';
                        $details2[$index]
                            ->setClienteTipo('1')
                            ->setClienteNro($dni);
                    }
                    $details2[$index]->setTotal($value['total'])
                        ->setMtoOperGravadas($value['total_gravada'])
                        ->setMtoOperInafectas($value['total_inafecta'])
                        ->setMtoIGV($value['total_impuesto']);
                }
            }

            $resumen = new Summary();
            $resumen->setFecGeneracion(new \DateTime(now())) // Fecha de emisión de las boletas.
                ->setFecResumen(new \DateTime(now())) // Fecha de envío del resumen diario.
                ->setCorrelativo($correlativo) // Correlativo, necesario para diferenciar de otros Resumen diario del mismo día.
                ->setCompany($this->empresa)
                /**
                 * $details => solo boletas
                 * $details1 => solo notas de debito
                 * $details2 => solo notas de credito
                 */
                ->setDetails($details, $details1, $details2);

            $resumen_diario = new ResumenDiario();
            $resumen_diario->fecha_envio = now();
            $resumen_diario->fecha_emision = now();
            $resumen_diario->correlativo = $correlativo;
            $resumen_diario->enviado = false;
            $resumen_diario->save();

            $result = $see->send($resumen);
            // Guardar XML
            $xmlGuardado = file_put_contents(
                storage_path('app/public/Sunat/XML/' .
                    $resumen->getName() . '.xml'),
                $see->getFactory()->getLastXml()
            );
            if ($xmlGuardado) {
                $resumen_diario->url_xml =  $resumen->getName() . '.xml';
                $resumen_diario->update();
            }

            if (!$result->isSuccess()) {
                // Si hubo error al conectarse al servicio de SUNAT.
                $resumen_diario->observaciones = var_dump($result->getError());
                $resumen_diario->update();
                $resultado = ['errorMessage' => var_dump($result->getError()), 'error' => true];
                return $resultado;
            }

            $ticket = $result->getTicket();
            $resumen_diario->ticket = $ticket . PHP_EOL;
            $resumen_diario->update();

            $statusResult = $see->getStatus($ticket);
            if (!$statusResult->isSuccess()) {
                // Si hubo error al conectarse al servicio de SUNAT.
                $resumen_diario->observaciones = var_dump($statusResult->getError());
                $resumen_diario->update();
                $resultado = ['errorMessage' => var_dump($statusResult->getError()), 'error' => true];
                return $resultado;
            }

            $cdrGuardado = file_put_contents(storage_path('app/public/Sunat/CDR/' . 'R-' . $resumen->getName() . '.zip'), $statusResult->getCdrZip());
            if ($cdrGuardado) {
                $resumen_diario->url_cdr = 'R-' . $resumen->getName() . '.zip';
                $resumen_diario->update();
            }

            //echo $statusResult->getCdrResponse()->getDescription();
            $cdr = $statusResult->getCdrResponse();
            $code = (int)$cdr->getCode();

            if ($code === 0) {
                $resumen_diario->estado = 'aceptado';
                $resumen_diario->observaciones = $cdr->getDescription() . PHP_EOL;
                $resumen_diario->update();
            } else if ($code === 98) {
                $resumen_diario->estado = 'observado';
                $resumen_diario->observaciones =  $cdr->getDescription() . PHP_EOL;;
                $resumen_diario->update();
            } else if ($code === 99) {
                $resumen_diario->estado = 'rechazado';
                $resumen_diario->observaciones = $cdr->getDescription() . PHP_EOL;
                $resumen_diario->update();
            }
            $resultado = [
                'successMessage' => 'Resumen diario enviado con exito',
                'error' => false
            ];
        } catch (Exception $e) {
            $resultado = ['errorMessage' => $e->getMessage(), 'error' => true];
            Log::error('BoletaController@resumenDiario, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
            return $resultado;
        }
        foreach ($boletas as $index => $value) {
            $boleta = Comprobante::where('id', 'like', $value['id'])->first();
            $boleta->estado = 'aceptado';
            $boleta->enviado = true;
            $boleta->update();
            $notas_debito = Comprobante::where('comprobante_afectado_id', $boleta->id)->where('tipo_comprobante_id', 3)->first();
            $notas_debito->estado = 'aceptado';
            $notas_debito->enviado = true;
            $notas_debito->update();
            /*foreach ($notas_debito as $index => $value) {
                $notas_debito[$index]->estado = 'aceptado';
                $notas_debito[$index]->enviado = true;
                $notas_debito->update();
            }*/
            $notas_credito = Comprobante::where('comprobante_afectado_id', $boleta->id)->where('tipo_comprobante_id', 4)->first();
            $notas_credito->estado = 'aceptado';
            $notas_credito->enviado = true;
            $notas_credito->update();
            /*foreach ($notas_credito as $index => $value) {
                $notas_credito[$index]->estado = 'aceptado';
                $notas_credito[$index]->enviado = true;
                $notas_credito[$index]->update();
            }*/


            $data = [
                'adjuntoPDF' => storage_path('app/public/Sunat/PDF/' . $value['serie'] . '-' . $value['correlativo'] . '.pdf'),
                'adjuntoTicket' => storage_path('app/public/Sunat/PDF/' . $value['serie'] . '-' . $value['correlativo'] . '-ticket' . '.pdf'),
                'email' => $value['email']
            ];

            EnviarCorreosJob::dispatch($data);
        }

        return $resultado;
        //return redirect()->route('cobros.iniciar');
    }
    public function anular(Comprobante $boleta)
    {
        try {
            $boleta->estado = 'anulado';
            $boleta->observaciones = '';
            $boleta->update();
        } catch (Exception $e) {
            Log::error('BoletaController@anular, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('boletas.iniciar');
    }

    /*$html = new HtmlReport();
            $html->setTemplate('summary.html.twig');

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

            $pdf = $report->render($resumen, $params);

            if ($pdf === null) {
                $error = $report->getExporter()->getError();
                echo 'Error: ' . $error;
                return;
            }

            $pdfGuardado = file_put_contents(storage_path('app/public/Sunat/PDF/' . $resumen->getName() . '.pdf'), $pdf);
            if ($pdfGuardado) {
                $resumen_diario->url_pdf = $resumen->getName() . '.pdf';
                $resumen_diario->update();
            }*/
}
