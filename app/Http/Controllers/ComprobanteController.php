<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\Matricula;
use App\Models\Escuela;
use App\Models\Alumno;
use App\Models\Docente;
use App\Models\Dependencia;
use App\Models\DetallesComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
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

    public function create(Request $request)
    {                   
        //dd($request->dependencia);
        
        $comprobante = new Comprobante();

        $comprobante->codigo = "";
        $comprobante->cui = "";
        $comprobante->escuela = "";
        $comprobante->nues = "";
        $comprobante->serie = "";
        $comprobante->correlativo = "";
        $comprobante->total = "";
        $comprobante->observaciones = "";
        $comprobante->url_xml = "";
        $comprobante->url_cdr = "";
        $comprobante->detalles = array();

        $tipo_usuario = $request->tipo_usuario;

        if ($tipo_usuario == 'alumno') {
            $comprobante->cui = $request->alumno['cui'];
            $comprobante->dni = $request->alumno['dic'];
            $comprobante->escuela = $request->matricula['escuela']['nesc'];
            $comprobante->nues = $request->matricula['nues'];
            $comprobante->usuario = $request->alumno['apn'];            
        }        
        else if ($tipo_usuario == 'docente') {            
            $comprobante->codigo = $request->docente['codper'];
            $comprobante->dni = $request->docente['dic'];            
            $comprobante->usuario = $request->docente['apn'];            
        }
        else if ($tipo_usuario == 'dependencia') {          
            $comprobante->codigo = $request->dependencia['codi_depe'];            
            $comprobante->usuario = $request->dependencia['nomb_depe'];            
        }

        $conceptos = Concepto::select('id', 'codigo as value', 'descripcion as text', 'precio', 'estado')
            ->orderBy('descripcion', 'asc')
            ->get();

        return Inertia::render('Comprobantes/Detalles', compact('comprobante', 'conceptos'));        
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        $ultimo = Comprobante::latest('created_at')->first();
        $ultimo->correlativo += 1;

        try {
            $comprobante = new Comprobante();

            $comprobante->codigo = $request->codigo;
            $comprobante->cui = $request->cui;
            $comprobante->nues = $request->nues;
            $comprobante->serie = 'B001';
            $comprobante->correlativo = str_pad($ultimo->correlativo, 8, "0", STR_PAD_LEFT);
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
}
