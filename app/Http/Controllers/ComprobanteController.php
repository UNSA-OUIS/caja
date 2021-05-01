<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
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

        $query = Comprobante::with('detalles')->where('codigo', 'like', '%' . $request->filter . '%');

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

    public function create(Request $request)
    {
        if ($request->has('tipo_usuario')) {
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

            if ($comprobante->tipo_usuario == 'alumno') {
                $comprobante->codigo = $request->alumno['cui'];
                $comprobante->dni = $request->alumno['dic'];
                $comprobante->escuela = $request->matricula['escuela']['nesc'];
                $comprobante->nues = $request->matricula['nues'];
                $comprobante->usuario = $request->alumno['apn'];
            } else if ($comprobante->tipo_usuario == 'docente') {
                $comprobante->codigo = $request->docente['codper'];
                $comprobante->dni = $request->docente['dic'];
                $comprobante->usuario = $request->docente['apn'];
            } else if ($comprobante->tipo_usuario == 'dependencia') {
                $comprobante->codigo = $request->dependencia['codi_depe'];
                $comprobante->usuario = $request->dependencia['nomb_depe'];
            } else if ($comprobante->tipo_usuario == 'particular') {
                $comprobante->dni = $request->particular['dni'];
                $comprobante->email = $request->particular['email'];
                $comprobante->usuario = $request->particular['apellidos'] . ", " . $request->particular['nombres'];
            }
            else if ($comprobante->tipo_usuario == 'empresa') {
                $comprobante->ruc = $request->empresa['ruc'];
                $comprobante->razon_social = $request->empresa['razon_social'];
                $comprobante->email = $request->empresa['email'];
                $comprobante->direccion = $request->empresa['direccion'];                
            }

            return Inertia::render('Comprobantes/Detalles', compact('comprobante'));
        } else {
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

            $detalles = $request->detalles;
            foreach ($detalles as $index => $detalle) {
                $detalle_comprobante = new DetallesComprobante();
                $detalle_comprobante->cantidad = $detalle['cantidad'];
                $detalle_comprobante->valor_unitario =  $detalle['precio'];
                $detalle_comprobante->descuento =  $detalle['descuento'];
                $detalle_comprobante->concepto_id =  $detalle['id'];
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
