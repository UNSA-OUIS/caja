<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Concepto;
use App\Models\TiposConcepto;
use App\Models\Clasificador;
use App\Models\UnidadMedida;
use Illuminate\Http\Request;
use App\Http\Requests\ConceptoStoreRequest;
use App\Http\Requests\ConceptoUpdateRequest;
use App\Models\CuentaCorriente;
use App\Models\Dependencia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Expr\AssignOp\Concat;

class ConceptoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("viewAny", Concepto::class);

        $query = Concepto::with('tipo_concepto')
            ->with('clasificador')
            ->with('unidad_medida')
            ->where('descripcion', 'like', '%' . $request->filter . '%')
            ->orderBy('id', 'desc');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        } else {
            $query = $query->withTrashed();
        }

        return $query->paginate($request->size);
    }

    public function create()
    {
        $concepto = new Concepto();

        $concepto->codigo = null;
        $concepto->descripcion = "";
        $concepto->descripcion_imp = "";
        $concepto->precio = "";
        $concepto->tipo_precio = "";
        $concepto->tipo_afectacion = null;
        $concepto->tipo_concepto_id = null;
        $concepto->clasificador_id = null;
        $concepto->unidad_medida_id = null;
        $concepto->semestre = null;
        $concepto->codi_depe = "";
        $concepto->detraccion = false;

        $tipos_concepto = TiposConcepto::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')
            ->get();

        $clasificadores = Clasificador::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')
            ->get();

        $unidades_medida = UnidadMedida::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')
            ->get();

        $cuentas_corrientes = CuentaCorriente::select('id as value', DB::raw("CONCAT(numero_cuenta, ' | ', descripcion) as text"))
            ->orderBy('id', 'asc')
            ->get();

        return Inertia::render(
            'Conceptos/NuevoMostrar',
            compact('concepto', 'tipos_concepto', 'clasificadores', 'unidades_medida')
        );
    }

    public function store(ConceptoStoreRequest $request)
    {
        try {
            $concepto = new Concepto();
            $concepto->codigo = $request->codigo;
            $concepto->descripcion = $request->descripcion;
            $concepto->descripcion_imp = $request->descripcion_imp;
            $concepto->tipo_precio = $request->tipo_precio;
            if ($concepto->tipo_precio == "variable") {
                $concepto->precio = "";
            } elseif ($concepto->tipo_precio == "fijo") {
                $concepto->precio = $request->precio;
            }
            $concepto->tipo_afectacion = $request->tipo_afectacion;
            $concepto->tipo_concepto_id = $request->tipo_concepto_id;
            $concepto->clasificador_id = $request->clasificador_id;
            $concepto->unidad_medida_id = $request->unidad_medida_id;
            $concepto->semestre = $request->semestre;
            $concepto->codi_depe = $request->codi_depe;
            $concepto->detraccion = $request->detraccion;
            $concepto->save();
            $result = ['successMessage' => 'Concepto registrado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el concepto' . $e];
            Log::error('ConceptoController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('conceptos.iniciar')->with($result);
    }

    public function show(Concepto $concepto)
    {
        $tipos_concepto = TiposConcepto::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')
            ->get();

        $clasificadores = Clasificador::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')
            ->get();

        $unidades_medida = UnidadMedida::select('id as value', 'nombre as text')
            ->orderBy('nombre', 'asc')
            ->get();

        return Inertia::render('Conceptos/NuevoMostrar', compact('concepto', 'tipos_concepto', 'clasificadores', 'unidades_medida'));
    }

    public function edit($id)
    {
        //
    }

    public function update(ConceptoUpdateRequest $request, Concepto $concepto)
    {
        try {
            $concepto->codigo = $request->codigo;
            $concepto->descripcion = $request->descripcion;
            $concepto->descripcion_imp = $request->descripcion_imp;
            $concepto->precio = $request->precio;
            $concepto->tipo_precio = $request->tipo_precio;
            $concepto->tipo_afectacion = $request->tipo_afectacion;
            $concepto->tipo_concepto_id = $request->tipo_concepto_id;
            $concepto->clasificador_id = $request->clasificador_id;
            $concepto->unidad_medida_id = $request->unidad_medida_id;
            $concepto->semestre = $request->semestre;
            $concepto->codi_depe = $request->codi_depe;
            $concepto->update();
            $result = ['successMessage' => 'Concepto actualizado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el concepto'];
            Log::error('ConceptoController@update, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('conceptos.iniciar')->with($result);
    }

    public function destroy(Concepto $concepto)
    {
        try {
            $concepto->estado = false;
            $concepto->update();
            $concepto->delete();
            $result = ['successMessage' => 'Concepto eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el concepto'];
            Log::error('ConceptoController@destroy, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function restore($concepto_id)
    {
        try {
            $concepto = Concepto::withTrashed()->findOrFail($concepto_id);
            $concepto->restore();
            $result = ['successMessage' => 'Concepto restaurado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el concepto'];
            Log::error('ConceptoController@restore, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->back()->with($result);
    }
    public function buscarCentroCosto(Request $request)
    {
        $filtro = $request->filtro;

        $centroCostos = Dependencia::where('codi_depe', 'like', '%' . $filtro . '%')
            ->orWhere('nomb_depe', 'like', '%' . $filtro . '%')
            ->select('codi_depe', 'nomb_depe')
            ->orderBy('codi_depe', 'asc')
            ->get();

        return $centroCostos;
    }

    public function buscarConcepto(Request $request)
    {
        $filtro = $request->filtro;

        $conceptos = Concepto::where('descripcion', 'ilike', '%' . $filtro . '%')
            ->orWhere('codigo', 'ilike', '%' . $filtro . '%')
            ->select('id as concepto_id', 'codigo', 'descripcion', 'precio','tipo_precio', 
                      DB::raw("(CONCAT(codigo, ' - ', descripcion)) AS vista_concepto"))
            ->orderBy('descripcion', 'asc')
            ->get();

        return $conceptos;
    }
}
