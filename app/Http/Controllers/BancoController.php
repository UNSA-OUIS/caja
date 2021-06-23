<?php

namespace App\Http\Controllers;

use App\Models\BancoBCP;
use Illuminate\Http\Request;

class BancoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //$this->authorize("viewAny", Comprobante::class);

        $query = BancoBCP::select('concepto')
            ->whereDate('frecepcion', '>=', $request->fecha_inicio)
            ->whereDate('frecepcion', '<=', $request->fecha_fin)
            ->selectRaw('count(concepto) as cantidad')
            ->selectRaw('sum(mont_pag) as monto_acumulado')
            ->selectRaw("DATE_FORMAT(fpago,'%Y-%m-%d') as fecha_pago")
            ->selectRaw("DATE_FORMAT(frecepcion,'%Y-%m-%d') as fecha_recepcion")
            ->groupBy('concepto', 'fecha_pago', 'fecha_recepcion')
            ->orderBy('concepto', 'ASC')
            ->get();

        return $query;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
