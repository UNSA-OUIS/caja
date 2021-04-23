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
use App\Models\Dependencia;
use Illuminate\Support\Facades\Log;

class ConceptoController extends Controller
{
    public function index(Request $request)
    {
        $this->authorize("viewAny", Concepto::class);

        $query = Concepto::with('tipo_concepto')
                    ->with('clasificador')
                    ->with('unidad_medida')
                    ->where('descripcion', 'like', '%' . $request->filter . '%');

        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }
        else {
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
        $concepto->tipo_afectacion = "";
        $concepto->tipo_concepto_id = null;
        $concepto->clasificador_id = null;
        $concepto->unidad_medida_id = null;
        $concepto->cuenta_corriente = "";
        $concepto->semestre = "";

        $tipos_concepto = TiposConcepto::select('id as value', 'nombre as text')
                            ->orderBy('nombre', 'asc')
                            ->get();

        $clasificadores = Clasificador::select('id as value', 'nombre as text')
                            ->orderBy('nombre', 'asc')
                            ->get();

        $unidades_medida = UnidadMedida::select('id as value', 'nombre as text')
                            ->orderBy('nombre', 'asc')
                            ->get();

        return Inertia::render('Conceptos/NuevoMostrar',
            compact('concepto', 'tipos_concepto', 'clasificadores', 'unidades_medida'));
    }

    public function store(ConceptoStoreRequest $request)
    {
        try {
            $concepto = new Concepto();
            $concepto->codigo = $request->codigo;
            $concepto->descripcion = $request->descripcion;
            $concepto->descripcion_imp = $request->descripcion_imp;
            $concepto->precio = $request->precio;
            $concepto->tipo_precio = $request->tipo_precio;
            $concepto->tipo_afectacion = $request->tipo_afectacion;
            $concepto->tipo_concepto_id = $request->tipo_concepto_id;
            $concepto->clasificador_id = $request->clasificador_id;
            $concepto->unidad_medida_id = $request->unidad_medida_id;
            $concepto->cuenta_corriente = $request->cuenta_corriente;
            $concepto->semestre = $request->semestre;
            $concepto->save();
            $result = ['successMessage' => 'Concepto registrado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el concepto'];
            Log::error('ConceptoController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
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
            $concepto->cuenta_corriente = $request->cuenta_corriente;
            $concepto->semstre = $request->semestre;
            $concepto->update();
            $result = ['successMessage' => 'Concepto actualizado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el concepto'];
            Log::error('ConceptoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('conceptos.iniciar')->with($result);
    }

    public function destroy(Concepto $concepto)
    {
        try {
            $concepto->delete();
            $result = ['successMessage' => 'Concepto eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el concepto'];
            Log::error('ConceptoController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
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
            Log::error('ConceptoController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->back()->with($result);
    }
    public function buscarCentroCosto(Request $request)
    {
        $filtro = $request->filtro;

        $centroCostos = Dependencia::where('codi_depe', 'like', '%' . $filtro . '%')
                        ->orWhere('nomb_depe', 'like', '%' . $filtro . '%')
                        ->select('codi_depe', 'nomb_depe', 'nomb_depe_ant', 'noms_depe', 'noms_depe_ant')
                        ->orderBy('codi_depe', 'asc')
                        ->get();

        return $centroCostos;
    }
}
