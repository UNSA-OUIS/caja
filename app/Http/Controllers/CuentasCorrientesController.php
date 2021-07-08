<?php

namespace App\Http\Controllers;

use App\Http\Requests\CuentaCorrienteStoreRequest;
use App\Http\Requests\CuentaCorrienteUpdateRequest;
use App\Models\CuentaCorriente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class CuentasCorrientesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $query = CuentaCorriente::where('banco', 'like', '%' . $request->filter . '%');

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

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $cuentaCorriente = new CuentaCorriente();

        $cuentaCorriente->numero_cuenta = "";
        $cuentaCorriente->banco = null;
        $cuentaCorriente->moneda = null;

        return Inertia::render('Cuentas_Corrientes/NuevoMostrar', compact('cuentaCorriente'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CuentaCorrienteStoreRequest $request)
    {
        try {
            $cuentaCorriente = new CuentaCorriente();

            $cuentaCorriente->numero_cuenta = $request->numero_cuenta;
            $cuentaCorriente->banco = $request->banco;
            $cuentaCorriente->moneda = $request->moneda;
            $cuentaCorriente->save();
            $result = ['successMessage' => 'Cuenta corriente registrada con éxito'];
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo registrar la cuenta corriente'];
            Log::error('CuentasCorrientesController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('cuentas-corrientes.iniciar')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(CuentaCorriente $cuentaCorriente)
    {
        return Inertia::render('Cuentas_Corrientes/NuevoMostrar', compact('cuentaCorriente'));
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
    public function update(CuentaCorrienteUpdateRequest $request, CuentaCorriente $cuentaCorriente)
    {
        try {
            $cuentaCorriente->numero_cuenta = $request->numero_cuenta;
            $cuentaCorriente->banco = $request->banco;
            $cuentaCorriente->moneda = $request->moneda;
            $cuentaCorriente->update();
            $result = ['successMessage' => 'Cuenta corriente actualizada con éxito'];
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo actualizar la cuenta corriente'];
            Log::error('CuentasCorrientesController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('cuentas-corrientes.iniciar')->with($result);

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(CuentaCorriente $cuentaCorriente)
    {
        try {
            $cuentaCorriente->delete();
            $result = ['successMessage' => 'Cuenta corriente eliminada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar la cuenta corriente'];
            Log::error('CuentasCorrientesController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function restore($cuentaCorriente_id)
    {
        try {
            $cuentaCorriente = CuentaCorriente::withTrashed()->findOrFail($cuentaCorriente_id);
            $cuentaCorriente->restore();
            $result = ['successMessage' => 'Cuenta corriente restaurada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar la cuenta corriente'];
            Log::error('CuentasCorrientesController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->back()->with($result);
    }
}
