<?php

namespace App\Http\Controllers;

use App\Jobs\EnviarCorreosJob;
use App\Models\Alumno;
use App\Models\Comprobante;
use App\Models\Docente;
use App\Models\Particular;
use App\Models\ResumenDiario;
use Exception;
use Greenter\Model\Company\Address;
use Greenter\Model\Company\Company;
use Greenter\Model\Sale\Document;
use Greenter\Model\Summary\Summary;
use Greenter\Model\Summary\SummaryDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')->with('detalles.concepto')
            ->where('tipo_comprobante_id', 1)
            ->whereIn('estado', ['no_enviado', 'anulado'])
            ->whereDate('created_at', '>=', $request->fecha_inicio)
            ->whereDate('created_at', '<=', $request->fecha_fin)
            ->where('cajero_id', Auth::user()->id)->get();
        return $query;
    }


    public function resumenDiario(Request $request)
    {
        DB::beginTransaction();
        try {
            $see = require config_path('Sunat/config.php');
            $boletas = $request->all();
            $correlativo = '';
            $details1 = [];
            $details2 = [];

            $ultimo = ResumenDiario::latest('created_at')->first();
            if (!$ultimo) {
                $correlativo = '001';
            } else {
                $ultimo->correlativo += 1;
                $correlativo = str_pad($ultimo->correlativo, 3, "0", STR_PAD_LEFT);
            }
            /**
             * Recorro todas las boletas y verifico si es que existe alguna nota de debito o credito que afecte a las boletaas
             * que se envian para anexarlas al resumen diario*/
            foreach ($boletas as $index => $value) {
                $details[$index] = (new SummaryDetail())
                    ->setTipoDoc('03') // Boleta
                    ->setSerieNro($value['serie'] . '-' . $value['correlativo']);
                if ($value['estado'] == 'anulado') {
                    $details[$index]->setEstado('3'); // Anulación
                } else {
                    $details[$index]->setEstado('1'); // Emision
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
                $id_boleta = $value['id'];
                $notas_debito = Comprobante::where('comprobante_afectado_id', $id_boleta)->where('tipo_comprobante_id', 3)->where('estado', 'no_enviado')->get();
                if ($notas_debito) {
                    foreach ($notas_debito as $index => $value) {
                        if ($value['comprobante_afectado_id'] == $id_boleta) {
                            $boleta = Comprobante::where('id', $id_boleta)->first();
                            $details1[$index] = (new SummaryDetail())
                                ->setTipoDoc('08') // Nota de debito
                                ->setSerieNro($value['serie'] . '-' . $value['correlativo'])
                                ->setDocReferencia((new Document)
                                    ->setTipoDoc(03)
                                    ->setNroDoc($boleta->serie . '-' . $boleta->correlativo));
                            if ($value['estado'] == 'anulado') {
                                $details1[$index]->setEstado('3'); // Anulación
                            } else {
                                $details1[$index]->setEstado('1');
                            }
                            if ($boleta->tipo_usuario == 'alumno') {
                                $alumno = Alumno::where('cui', $boleta->codi_usuario)->first();
                                $dni = substr($alumno->dic, 1);
                                $details1[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            } elseif ($boleta->tipo_usuario == 'particular') {
                                $particular = Particular::where('dni', $boleta->codi_usuario)->first();
                                $dni = $particular->dni;
                                $details1[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            } elseif ($boleta->tipo_usuario == 'docente') {
                                $docente = Docente::where('codper', $boleta->codi_usuario)->first();
                                $dni = $docente->dic;
                                $details1[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            } elseif ($boleta->tipo_usuario == 'dependencia') {
                                $dni = '72351610';
                                $details1[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            }
                            $details1[$index]->setTotal($boleta->total)
                                ->setMtoOperGravadas($boleta->total_gravada)
                                ->setMtoOperInafectas($boleta->total_inafecta)
                                ->setMtoIGV($boleta->total_impuesto);
                        }
                    }
                }
                $notas_credito = Comprobante::where('comprobante_afectado_id', $value['id'])->where('tipo_comprobante_id', 4)->where('estado', 'no_enviado')->get();
                if ($notas_credito) {
                    foreach ($notas_credito as $index => $value) {
                        if ($value['comprobante_afectado_id'] == $id_boleta) {
                            $boleta = Comprobante::where('id', $id_boleta)->first();
                            $details2[$index] = (new SummaryDetail())
                                ->setTipoDoc('08') // Nota de debito
                                ->setSerieNro($value['serie'] . '-' . $value['correlativo'])
                                ->setDocReferencia((new Document)
                                    ->setTipoDoc(03)
                                    ->setNroDoc($boleta->serie . '-' . $boleta->correlativo));
                            if ($value['estado'] == 'anulado') {
                                $details2[$index]->setEstado('3'); // Anulación
                            } else {
                                $details2[$index]->setEstado('1');
                            }
                            if ($boleta->tipo_usuario == 'alumno') {
                                $alumno = Alumno::where('cui', $boleta->codi_usuario)->first();
                                $dni = substr($alumno->dic, 1);
                                $details2[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            } elseif ($boleta->tipo_usuario == 'particular') {
                                $particular = Particular::where('dni', $boleta->codi_usuario)->first();
                                $dni = $particular->dni;
                                $details2[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            } elseif ($boleta->tipo_usuario == 'docente') {
                                $docente = Docente::where('codper', $boleta->codi_usuario)->first();
                                $dni = $docente->dic;
                                $details2[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            } elseif ($boleta->tipo_usuario == 'dependencia') {
                                $dni = '72351610';
                                $details2[$index]
                                    ->setClienteTipo('1')
                                    ->setClienteNro($dni);
                            }
                            $details2[$index]->setTotal($boleta->total)
                                ->setMtoOperGravadas($boleta->total_gravada)
                                ->setMtoOperInafectas($boleta->total_inafecta)
                                ->setMtoIGV($boleta->total_impuesto);
                        }
                    }
                }
            }

            $resumen = new Summary();
            $resumen->setFecGeneracion(new \DateTime(now())) // Fecha de emisión de las boletas.
                ->setFecResumen(new \DateTime(now())) // Fecha de envío del resumen diario.
                ->setCorrelativo($correlativo) // Correlativo, necesario para diferenciar de otros Resumen diario del mismo día.
                ->setCompany($this->empresa);
            /**
             * $details => solo boletas
             * $details1 => solo notas de debito
             * $details2 => solo notas de credito
             */
            if ($details1 != '' && $details2 != '') {
                $resumen->setDetails($details, $details1, $details2);
            } elseif ($details1 != '' || $details2 != '') {
                if ($details1 != '') {
                    $resumen->setDetails($details, $details1);
                } elseif ($details2 != '') {
                    $resumen->setDetails($details, $details2);
                }
            } else {
                $resumen->setDetails($details);
            }

            $resumen_diario = new ResumenDiario();
            $resumen_diario->fecha_envio = now();
            $resumen_diario->fecha_emision = now();
            $resumen_diario->correlativo = $correlativo;
            $resumen_diario->estado = 'no_enviado';
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
                // $resumen_diario->observaciones = var_dump($result->getError());
                // $resumen_diario->update();
                $resultado = ['errorMessage' => var_dump($result->getError()), 'error' => true];
                DB::rollback();
                return $resultado;
            }

            $ticket = $result->getTicket();
            $resumen_diario->ticket = $ticket . PHP_EOL;
            $resumen_diario->update();

            $statusResult = $see->getStatus($ticket);
            if (!$statusResult->isSuccess()) {
                // Si hubo error al conectarse al servicio de SUNAT.
                //$resumen_diario->observaciones = var_dump($statusResult->getError());
                //$resumen_diario->update();
                $resultado = ['errorMessage' => var_dump($statusResult->getError()), 'error' => true];
                DB::rollback();
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
                'successMessage' => 'Boletas enviadas con exito',
                'error' => false
            ];

            foreach ($boletas as $index => $value) {
                $boleta = Comprobante::where('id', 'like', $value['id'])->first();
                $boleta->estado = 'aceptado';
                $boleta->update();
                $id_boleta = $value['id'];
                $notas_debito = Comprobante::where('comprobante_afectado_id', $id_boleta)->where('tipo_comprobante_id', 3)->where('estado', 'no_enviado')->get();
                if ($notas_debito) {
                    foreach ($notas_debito as $index => $value) {
                        $notas_debito[$index]->estado = 'aceptado';
                        $notas_debito->update();
                    }
                }
                $notas_credito = Comprobante::where('comprobante_afectado_id', $id_boleta)->where('tipo_comprobante_id', 4)->where('estado', 'no_enviado')->get();
                if ($notas_credito) {
                    foreach ($notas_credito as $index => $value) {
                        $notas_credito[$index]->estado = 'aceptado';
                        $notas_credito->update();
                    }
                }

                $data = [
                    'adjuntoPDF' => storage_path('app/public/Sunat/PDF/' . $value['serie'] . '-' . $value['correlativo'] . '.pdf'),
                    'adjuntoTicket' => storage_path('app/public/Sunat/PDF/' . $value['serie'] . '-' . $value['correlativo'] . '-ticket' . '.pdf'),
                    'email' => $value['email']
                ];

                EnviarCorreosJob::dispatch($data);
            }
            DB::commit();
        } catch (Exception $e) {
            $resultado = ['errorMessage' => $e->getMessage(), 'error' => true];
            Log::error('BoletaController@resumenDiario, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
            DB::rollback();
            return $resultado;
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
}
