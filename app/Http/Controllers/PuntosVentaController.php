<?php

namespace App\Http\Controllers;

use App\Http\Requests\PuntosVentaStoreRequest;
use App\Http\Requests\PuntosVentaUpdateRequest;
use App\Models\PuntosVenta;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Spatie\Permission\Models\Role;

class PuntosVentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $query = PuntosVenta::with('user')->where('nombre', 'like', '%' . $request->filter . '%');
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
        $puntoVenta = new PuntosVenta();
        $puntoVenta->ip = "";
        $puntoVenta->nombre = "";
        $puntoVenta->direccion = "";
        $puntoVenta->user_id = null;

        $usuarios = User::select('id as value', 'name as text')
            ->whereHas('roles', function ($q) {
                $q->where('roles.name', '=', 'Cajero'); // or whatever constraint you need here
            })
            ->orderBy('name', 'asc')->get();

        /*$cajeroRole = Role::where('name', 'Cajero')->first();
        dd($cajeroRole);
        $usuarios = $cajeroRole->users;*/
        
        return Inertia::render('Puntos_Venta/NuevoMostrar', compact('puntoVenta', 'usuarios'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PuntosVentaStoreRequest $request)
    {
        try {
            $puntoVenta = new PuntosVenta();
            $puntoVenta->nombre = $request->nombre;
            $puntoVenta->ip = $request->ip;
            $puntoVenta->direccion = $request->direccion;
            $puntoVenta->user_id = $request->user_id;
            $puntoVenta->save();
            $result = ['successMessage' => 'Punto de venta registrado con éxito'];

        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo registrar el punto de venta', 'error' => true];
            \Log::error('PuntosVentaController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('puntosVenta.iniciar')->with($result);
        
    }

    public function show(PuntosVenta $puntoVenta)
    {
        $usuarios = User::select('id as value', 'name as text')
            ->orderBy('name', 'asc')
            ->get();

        return Inertia::render('Puntos_Venta/NuevoMostrar', compact('puntoVenta', 'usuarios'));
    }

    public function edit($id)
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(PuntosVentaUpdateRequest $request, PuntosVenta $puntoVenta)
    {
        try {
            $puntoVenta->nombre = $request->nombre;
            $puntoVenta->ip = $request->ip;
            $puntoVenta->direccion = $request->direccion;
            $puntoVenta->user_id = $request->user_id;
            $puntoVenta->update();
            $result = ['successMessage' => 'Punto de venta actualizado con éxito'];

        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el punto de venta', 'error' => true];
            \Log::error('PuntosVentaController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('puntosVenta.iniciar')->with($result);
        
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(PuntosVenta $puntoVenta)
    {
        try {
            $puntoVenta->delete();
            $result = ['successMessage' => 'Punto de venta eliminado con éxito'];

        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el punto de venta'];
            \Log::error('PuntosVentaController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
            
        }

        return redirect()->back()->with($result);
    }

    public function restore($puntoVenta_id)
    {

        try {
            $puntoVenta = PuntosVenta::withTrashed()->findOrFail($puntoVenta_id);
            $puntoVenta->restore();
            $result = ['successMessage' => 'Punto de venta restaurado con éxito'];
            
        } catch (\Throwable $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el punto de venta'];
            \Log::warning('PuntosVentaController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->back()->with($result);
    }
}
