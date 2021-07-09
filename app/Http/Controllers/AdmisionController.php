<?php

namespace App\Http\Controllers;

use App\Models\Admision;
use App\Models\BancoBCP;
use App\Models\DetallesAdmision;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Monolog\Handler\FirePHPHandler;

class AdmisionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = Admision::with('detalles')->where('proceso_id', 'like', '%' . $request->filter . '%')
            ->orderBy('id', 'desc');
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
        $admision = new Admision();

        $admision->proceso_id = null;
        $admision->monto_total = "";
        $admision->tipo_colegio = null;
        $admision->detalles = array();

        return Inertia::render('Admision/Cabecera', compact('admision'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();

        try {
            $admision = new Admision();

            $admision->proceso_id = $request->proceso_id;
            $admision->monto_total = $request->monto_total;
            $admision->tipo_colegio = $request->tipo_colegio;
            $admision->save();

            $monto_concepto = 0;

            foreach ($request->detalles as $index => $detalle) {
                $detalle_admision = new DetallesAdmision();
                $detalle_admision->concepto_id =  $detalle['concepto_id'];
                $detalle_admision->admision_id =  $admision->id;
                $detalle_admision->precio_variable =  $detalle['precio'];
                $monto_concepto += $detalle['precio'];
                $detalle_admision->save();
            }
            if ($monto_concepto == $request->monto_total) {
                $result = [
                    'successMessage' => 'Registrado con exito',
                    'error' => false
                ];
                DB::commit();
            } else {
                $result = [
                    'errorMessage' => 'Los montos por concepto no completan el monto total',
                    'error' => true
                ];
                DB::rollback();
            }
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['errorMessage' => 'Error al registrar', 'error' => true];
            Log::error('AdmiisonController@store, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }
        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Admision $admision)
    {
        $admision = Admision::with('detalles.concepto')->where('id', $admision->id)->first();
        return Inertia::render('Admision/Cabecera', compact('admision'));
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function consulta(Request $request)
    {
        $query = BancoBCP::where('cod_bancario', $request->codigo_web);
        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        }

        return $query->paginate($request->size);
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

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
