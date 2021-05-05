<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Inertia\Inertia;
use App\Models\User;
use App\Models\Alumno;
use App\Models\Comprobante;
use App\Models\Dependencia;
use App\Jobs\EnviarCorreosJob;
use App\Mail\CobroRealizadoMailable;
use App\Models\DetallesComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Symfony\Component\HttpFoundation\JsonResponse;

class ComprobanteController extends Controller
{
    public function index(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = Comprobante::with('comprobanteable')->with('tipo_comprobante')
                    ->with('detalles')->where('codi_usuario', 'like', '%' . $request->filter . '%');                

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
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

    public function crear_alumno(Request $request)
    {
        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "alumno";
        $comprobante->codi_usuario = $request->alumno['cui'];
        $comprobante->nues_espe = $request->matricula['nues'];
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');
        $comprobante->serie = "B001";
        $comprobante->correlativo = "00000001";
        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'BOLETA',
            'dni' => $request->alumno['dic'],
            'escuela' => $request->matricula['escuela']['nesc'],
            'alumno' => $request->alumno['apn'],
            'email' => 'sizaisi@unsa.edu.pe',
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_docente(Request $request)
    {
        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "docente";
        $comprobante->codi_usuario = $request->docente['codper'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');
        $comprobante->serie = "B001";
        $comprobante->correlativo = "00000001";
        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'BOLETA',
            'dni' => $request->docente['dic'],
            'docente' => $request->docente['apn'],
            'email' => 'sizaisi@unsa.edu.pe',
            'departamento' => 'Informática y Sistemas',
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_dependencia(Request $request)
    {
        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "dependencia";
        $comprobante->codi_usuario = $request->dependencia['codi_depe'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');
        $comprobante->serie = "B001";
        $comprobante->correlativo = "00000001";
        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'BOLETA',
            'dependencia' => $request->dependencia['nomb_depe'],
            'email' => 'sizaisi@unsa.edu.pe',
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_particular(Request $request)
    {
        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "particular";
        $comprobante->codi_usuario = $request->particular['dni'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.BOLETA');
        $comprobante->serie = "B001";
        $comprobante->correlativo = "00000001";
        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'BOLETA',
            'particular' => $request->particular['apellidos'] . ", " . $request->particular['nombres'],
            'email' => $request->particular['email'],
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function crear_empresa(Request $request)
    {
        $comprobante = new Comprobante();

        $comprobante->tipo_usuario = "empresa";
        $comprobante->codi_usuario = $request->empresa['ruc'];
        $comprobante->nues_espe = "";
        $comprobante->tipo_comprobante_id = config('caja.tipo_comprobante.FACTURA');
        $comprobante->serie = "F001";
        $comprobante->correlativo = "00000001";
        $comprobante->total_descuento = "";
        $comprobante->total_impuesto = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        $data = [
            'tipo_comprobante' => 'FACTURA',
            'razon_social' => $request->empresa['razon_social'],
            'email' => $request->empresa['email'],
            'direccion' => $request->empresa['direccion'],
            'fecha_actual' => Carbon::now('America/Lima')->format('Y-m-d')
        ];

        return Inertia::render('Comprobantes/Cabecera', compact('comprobante', 'data'));
    }

    public function create(Request $request)
    {
        $ultimo = Comprobante::latest('created_at')->first();
        $comprobante = new Comprobante();
        $comprobante->tipo_usuario = $request->tipo_usuario;
        if (!$ultimo) {
            $comprobante->codigo = 1;
        } else {
            $ultimo->codigo += 1;
            $comprobante->codigo = $ultimo->codigo;
        }
        $comprobante->dni = "";
        $comprobante->ruc = "";
        $comprobante->razon_social = "";
        $comprobante->direccion = "";
        $comprobante->email = "";
        $comprobante->escuela = "";
        $comprobante->nues = "";
        $comprobante->serie = "F001";
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
    }

    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $comprobante = new Comprobante();

            $comprobante->tipo_usuario = $request->tipo_usuario;
            $comprobante->codi_usuario = $request->codi_usuario;
            $comprobante->nues_espe = $request->nues_espe;
            $comprobante->tipo_comprobante_id = $request->tipo_comprobante_id;
            $comprobante->serie = $request->serie;
            $comprobante->correlativo = $request->correlativo;
            $comprobante->total_descuento = 10.00;
            $comprobante->total_impuesto = 100.00;
            $comprobante->total = $request->total;
            $comprobante->estado = 'noEnviado';
            $comprobante->cajero_id = \Auth::user()->id;
            $comprobante->save();

            foreach ($request->detalles as $index => $detalle) {
                $detalle_comprobante = new DetallesComprobante();
                $detalle_comprobante->cantidad = $detalle['cantidad'];
                $detalle_comprobante->valor_unitario =  $detalle['precio'];
                $detalle_comprobante->descuento =  $detalle['descuento'];
                $detalle_comprobante->concepto_id =  $detalle['concepto_id'];
                $detalle_comprobante->comprobante_id =  $comprobante->id;
                $detalle_comprobante->save();
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return $e;
        }
        return redirect()->route('comprobantes.iniciar');
    }

    public function show(Comprobante $comprobante)
    {
        $comprobante = Comprobante::with('detalles')->where('id', 'like', $comprobante->id)->first();

        return Inertia::render('Comprobantes/Detalles', compact('comprobante'));
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
