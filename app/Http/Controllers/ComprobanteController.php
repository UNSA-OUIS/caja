<?php

namespace App\Http\Controllers;

use App\Models\Comprobante;
use App\Models\Concepto;
use App\Models\DetallesComprobante;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class ComprobanteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*comprobante: {
            codigo: "",
            cui: "",
            nues: "",
            serie: "",
            correlativo: "",
            submittedDetails: [
                {
                    codigo: "",
                    concepto_id: "",
                    cantidad: "1",
                    prUnit: "",
                    tipo_descuento: "",
                    descuento: "0.00"
                }
            ],
            total: ""
        },*/
        $comprobante = new Comprobante();
        $comprobante->codigo = "";
        $comprobante->cui = "";
        $comprobante->nues = "";
        $comprobante->serie = "";
        $comprobante->correlativo = "";
        $comprobante->total = "";
        $comprobante->detalles = array();

        //$detalles = new DetallesComprobante();
        //$detalles->cantidad = "";

        $conceptos = Concepto::select('id', 'codigo as value', 'descripcion as text', 'precio')
            ->orderBy('descripcion', 'asc')
            ->get();

        return Inertia::render('Comprobantes/Detalles', compact('comprobante', 'conceptos'));
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
            $comprobante = new Comprobante();

            $comprobante->codigo = $request->codigo;
            $comprobante->cui = $request->cui;
            $comprobante->nues = $request->nues;
            $comprobante->serie = $request->serie;
            $comprobante->correlativo = $request->correlativo;
            $comprobante->total = $request->total;
            $comprobante->estado = 1;
            $comprobante->save();

            $detalle = $request->detalles;
            foreach ($detalle as $index => $value) {
                if ($index <= count($detalle)) {
                    $detalles = new DetallesComprobante();
                    $detalles->cantidad = $value['cantidad'];
                    $detalles->valor_unitario =  $value['valor_unitario'];
                    $detalles->descuento =  $value['descuento'];
                    $detalles->estado =  $comprobante->estado;
                    $detalles->concepto_id =  $value['concepto_id'];
                    $detalles->comprobante_id =  $comprobante->id;
                    $detalles->save();
                }
            }
            DB::commit();
            $result = ['successMessage' => 'Comprobante registrado con éxito'];
        } catch (\Exception $e) {
            DB::rollback();
            $result = ['errorMessage' => 'No se pudo registrar el comprobante' . $e];
        }
        return redirect()->route('comprobantes.iniciar')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Comprobante $comprobante)
    {
        $comprobante = Comprobante::with('detalles')->where('id', 'like', $comprobante->id)->first();
        $conceptos = Concepto::select('id', 'codigo as value', 'descripcion as text', 'precio')
            ->orderBy('descripcion', 'asc')
            ->get();
        return Inertia::render('Comprobantes/Detalles', compact('comprobante', 'conceptos'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function anular(Comprobante $comprobante)
    {
        try {
            $comprobante->estado = 0;
            $comprobante->update();
            $result = ['successMessage' => 'Comprobante anulado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo anular el comprobante'];
            Log::error('ComprobanteController@anular, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return redirect()->route('comprobantes.iniciar')->with($result);
    }
}
