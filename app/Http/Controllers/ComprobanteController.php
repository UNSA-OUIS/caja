<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\Matricula;
use App\Models\Escuela;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Dependencia;
use App\Models\Particular;
use App\Http\Requests\ParticularStoreRequest;
use App\Jobs\EnviarCorreosJob;
use App\Mail\CobroRealizadoMailable;
use App\Models\DetallesComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\JsonResponse;

class ComprobanteController extends Controller
{
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

    public function buscarCuiAlumno($cui)
    {
        $alumno = Alumno::where('cui', $cui)->first();
        $matriculas = Matricula::with('escuela')->where('cui', $cui)->get();

        return [
            'alumno' => $alumno,
            'matriculas' => $matriculas
        ];
    }

    public function buscarApnAlumno(Request $request)
    {
        $alumnos = Alumno::where('apn', 'like', $request->ap_paterno . '%')
            //->orWhere('apn', 'like', '%' . $request->ap_materno)
            //->orWhere('apn', 'like', $request->nombres)
            ->take(10)
            ->get();

        return $alumnos;
    }

    public function buscarCodigoDocente($codigo)
    {
        $docente = Docente::where('codper', $codigo)->first();

        return json_encode($docente);
    }

    public function buscarApnDocente(Request $request)
    {
        $docentes = Docente::where('apn', 'like', $request->ap_paterno . '%')
            //->orWhere('apn', 'like', '%' . $request->ap_materno)
            //->orWhere('apn', 'like', $request->nombres)
            ->take(10)
            ->get();

        return $docentes;
    }

    public function buscarCodigoDependencia($codigo)
    {
        $dependencia = Dependencia::where('codi_depe', $codigo)->first();

        return json_encode($dependencia);
    }

    public function buscarDependencia($dependencia)
    {
        $dependencias = Dependencia::where('nomb_depe', 'like', $dependencia . '%')
            ->take(20)
            ->get();

        return $dependencias;
    }

    public function buscarDniParticular($dni)
    {
        $particular = Particular::where('dni', $dni)->first();

        return json_encode($particular);
    }

    public function buscarApnParticular(Request $request)
    {
        $particulares = Particular::where('apellidos', 'like', $request->ap_paterno . '%')
            ->take(10)
            ->get();

        return $particulares;
    }

    public function registrarParticular(ParticularStoreRequest $request)
    {
        try {
            $particular = new Particular();
            $particular->dni = $request->dni;
            $particular->nombres = $request->nombres;
            $particular->apellidos = $request->apellidos;
            $particular->email = $request->email;
            $particular->save();
            $result = ['successMessage' => 'Particular registrado con éxito', 'error' => false];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar al particular', 'error' => true];
            Log::error('ComprobanteController@registrarParticular, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return $result;
    }

    public function buscarConcepto(Request $request)
    {
        $filtro = $request->filtro;              
        
        $conceptos = Concepto::where('descripcion', 'ilike', '%' . $filtro . '%')
                        ->orWhere('codigo', 'ilike', '%' . $filtro . '%')
                        ->select('id', 'codigo', 'descripcion', 'precio')
                        ->orderBy('descripcion', 'asc')
                        ->get();

        return $conceptos;
    }

    public function create(Request $request)
    {   
        
        if ($request->has('tipo_usuario')) {
            $ultimo = Comprobante::latest('created_at')->first();                                     
            $comprobante = new Comprobante();
            $comprobante->tipo_usuario = $request->tipo_usuario;
            $comprobante->codigo = "";

            if (!$ultimo) {
                $comprobante->codigo = 1;
            } 
            else {
                $ultimo->codigo += 1;
                $comprobante->codigo = $ultimo->codigo;
            }
            
            $comprobante->dni = "";
            $comprobante->email = "";
            $comprobante->escuela = "";
            $comprobante->nues = "";
            $comprobante->serie = "B001";
            if (!$ultimo) {
                $comprobante->correlativo = '00000001';
            } else {
                $ultimo->correlativo += 1;
                $comprobante->correlativo = str_pad($ultimo->correlativo, 8, "0", STR_PAD_LEFT);
            }
            $comprobante->total = "";
            $comprobante->observaciones = "";
            $comprobante->url_xml = "";
            $comprobante->url_cdr = "";
            $comprobante->detalles = array();        

            if ($comprobante->tipo_usuario == 'alumno') {
                $comprobante->codigo = $request->alumno['cui'];
                $comprobante->dni = $request->alumno['dic'];
                $comprobante->escuela = $request->matricula['escuela']['nesc'];
                $comprobante->nues = $request->matricula['nues'];
                $comprobante->usuario = $request->alumno['apn'];
            } 
            else if ($comprobante->tipo_usuario == 'docente') {
                $comprobante->codigo = $request->docente['codper'];
                $comprobante->dni = $request->docente['dic'];
                $comprobante->usuario = $request->docente['apn'];
            }
            else if ($comprobante->tipo_usuario == 'dependencia') {          
                $comprobante->codigo = $request->dependencia['codi_depe'];            
                $comprobante->usuario = $request->dependencia['nomb_depe'];            
            }
            else if ($comprobante->tipo_usuario == 'particular') {          
                $comprobante->dni = $request->particular['dni'];                         
                $comprobante->email = $request->particular['email'];                         
                $comprobante->usuario = $request->particular['apellidos'] . ", " . $request->particular['nombres'];
            }

            return Inertia::render('Comprobantes/Detalles', compact('comprobante'));
        }
        else {
            return redirect()->route('comprobantes.iniciar');
        }       
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $comprobante = new Comprobante();

            $comprobante->codigo = $request->codigo;
            $comprobante->cui = $request->cui;
            $comprobante->nues = $request->nues;
            $comprobante->serie = $request->serie;
            $comprobante->correlativo = $request->correlativo;
            $comprobante->total = $request->total;
            $comprobante->estado = 'noEnviado';
            $comprobante->observaciones = '';
            $comprobante->url_xml = '';
            $comprobante->url_cdr = '';
            $comprobante->url_pdf = '';
            $comprobante->save();

            $detalle = $request->detalles;
            foreach ($detalle as $index => $value) {
                $detalles = new DetallesComprobante();
                $detalles->cantidad = $value['cantidad'];
                $detalles->valor_unitario =  $value['valor_unitario'];
                $detalles->descuento =  $value['descuento'];
                $detalles->concepto_id =  $value['concepto_id'];
                $detalles->comprobante_id =  $comprobante->id;
                $detalles->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e->getMessage();
        }
        return redirect()->route('comprobantes.iniciar');
    }

    public function show(Comprobante $comprobante)
    {
        $comprobante = Comprobante::with('detalles')->where('id', 'like', $comprobante->id)->first();

        $conceptos = Concepto::select('id', 'codigo as value', 'descripcion as text', 'precio')
            ->orderBy('descripcion', 'asc')
            ->get();
        return Inertia::render('Comprobantes/Detalles', compact('comprobante', 'conceptos'));
    }

    public function edit($id)
    {
        //
    }

    public function anular(Comprobante $comprobante)
    {
        try {
            $comprobante->estado = 'anulado';
            $comprobante->update();
            $result = ['successMessage' => 'Comprobante anulado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo anular el comprobante'];
            Log::error('ComprobanteController@anular, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('comprobantes.iniciar')->with($result);
    }

    public function enviarCorreo(Request $request)
    {
        EnviarCorreosJob::dispatch($request->to);

        //Mail::to($request->to)->queue(new CobroRealizadoMailable($request->to));
        //$result = ['successMessage' => 'Particular registrado con éxito', 'error' => false];
        //return $result;
    }
}
