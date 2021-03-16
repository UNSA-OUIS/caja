<?php

namespace App\Http\Controllers;

use App\Http\Requests\AccesoGoogleStoreRequest;
use App\Http\Requests\AccesoGoogleUpdateRequest;
use App\Models\AccesoGoogle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AccesoGoogleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = AccesoGoogle::where('nombre', 'like', '%' . $request->filter . '%');
        $sortby = $request->sortby;

        if ($sortby && !empty($sortby)) {
            $sortdirection = $request->sortdesc == "true" ? 'desc' : 'asc';
            $query = $query->orderBy($sortby, $sortdirection);
        } else {
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
        $accesoGoogle = new AccesoGoogle();
        $accesoGoogle->nombre = "";
        $accesoGoogle->correo = "";
        $accesoGoogle->cargo = "";

        return Inertia::render('AccesosGoogle/NuevoMostrar', compact('accesoGoogle'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AccesoGoogleStoreRequest $request)
    {
        $accesoGoogle = new AccesoGoogle();
        $accesoGoogle->nombre = $request->nombre;
        $accesoGoogle->correo = $request->correo;
        $accesoGoogle->cargo = $request->cargo;

        if ($accesoGoogle->save()) {
            $result = ['successMessage' => 'Acceso registrado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo registrar el acceso', 'error' => true];
        }

        return $result;
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(AccesoGoogle $accesoGoogle)
    {
        return Inertia::render('AccesosGoogle/NuevoMostrar', compact('accesoGoogle'));
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
    public function update(AccesoGoogleUpdateRequest $request, AccesoGoogle $accesoGoogle)
    {
        $accesoGoogle->nombre = $request->nombre;
        $accesoGoogle->correo = $request->correo;
        $accesoGoogle->cargo = $request->cargo;

        if ($accesoGoogle->update()) {
            $result = ['successMessage' => 'Acceso actualizado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo actualizar el acceso', 'error' => true];
        }

        return $result;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AccesoGoogle $accesoGoogle)
    {
        if ($accesoGoogle->delete()) {
            $result = ['successMessage' => 'Acceso eliminado con éxito', 'error' => false];
        } else {
            $result = ['errorMessage' => 'No se pudo eliminar el acceso', 'error' => true];
        }

        return $result;
    }

    public function restore($accesoGoogle_id)
    {
        $accesoGoogle = AccesoGoogle::withTrashed()->findOrFail($accesoGoogle_id);

        try {
            $accesoGoogle->restore();
            $result = ['successMessage' => 'Acceso restaurado con éxito', 'error' => false];
            
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el acceso', 'error' => true];
            \Log::warning('AccesoGoogleController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return $result;
    }
}
