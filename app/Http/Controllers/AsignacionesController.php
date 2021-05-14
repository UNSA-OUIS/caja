<?php

namespace App\Http\Controllers;

use App\Models\Asignaciones;
use App\Models\Comprobante;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Spatie\Permission\Models\Permission;

class AsignacionesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        ///$this->authorize("viewAny", User::class);

        $query = User::with('persona')->with('asignaciones')->where('name', 'like', '%' . $request->filter . '%')
            ->orWhere('email', 'like', '%' . $request->filter . '%');
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
    public function create(User $usuario)
    {
        $asignaciones = Asignaciones::with('user')->with('tipo_comprobante')->where('user_id', 'like', $usuario->id)->get();

        $asignacion = new Asignaciones();
        $asignacion->serie = "";
        $asignacion->correlativo = "";
        $asignacion->tipo_comprobante_id = "";
        $asignacion->user_id = $usuario->id;

        return Inertia::render('Asignar/NuevoMostrar', compact('asignacion', 'usuario', 'asignaciones'));
    }
    public function search(Request $request)
    {
        $asignaciones = Asignaciones::with('user')->with('tipo_comprobante')->where('user_id', 'like', $request->user_id)->get();
        $usuario = User::with('persona')->with('asignaciones')->where('id', 'like', '%' . $request->user_id . '%')->first();
        $ultimo_comprobante =  Asignaciones::where('user_id', '<>', $request->user_id)->latest()->first();

        $asignacion = new Asignaciones();
        if ($request->tipo_comprobante_id == 1) {
            $numero_serie = substr($ultimo_comprobante->serie, 1);
            $numero_serie += 1;
            settype($numero_serie, 'string');
            $asignacion->serie = "B" . '00' . $numero_serie;
        } elseif ($request->tipo_comprobante_id == 2) {
            $numero_serie = substr($ultimo_comprobante->serie, 1);
            $numero_serie += 1;
            settype($numero_serie, 'string');
            $asignacion->serie = "F" . '00' . $numero_serie;
        } elseif ($request->tipo_comprobante_id == 3) {
            $numero_serie = substr($ultimo_comprobante->serie, 1);
            $numero_serie += 1;
            settype($numero_serie, 'string');
            $asignacion->serie = "B" . '00' . $numero_serie;
        } elseif ($request->tipo_comprobante_id == 4) {
            $numero_serie = substr($ultimo_comprobante->serie, 1);
            $numero_serie += 1;
            settype($numero_serie, 'string');
            $asignacion->serie = "F" . '00' . $numero_serie;
        }
        $asignacion->correlativo = '00000001';
        $asignacion->tipo_comprobante_id = $request->tipo_comprobante_id;
        $asignacion->user_id = $usuario->id;

        return Inertia::render('Asignar/NuevoMostrar', compact('asignacion', 'usuario', 'asignaciones'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $asignacion = new Asignaciones();

            $asignacion->serie = $request->serie;
            $asignacion->correlativo = $request->correlativo;
            $asignacion->tipo_comprobante_id = $request->tipo_comprobante_id;
            $asignacion->user_id = $request->user_id;
            $asignacion->save();

            $result = ['successMessage' => 'Asignado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo asignar' . $e];
        }

        return redirect()->route('usuarios.asignar')->with($result);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $usuario)
    {
        $roles = Rol::select('name as value', 'name as text')->orderBy('name', 'asc')->get();
        $usuario->roles_seleccionados = $usuario->getRoleNames();

        $permissions = Permission::all();
        $all_permissions = $usuario->getAllPermissions();

        $permisos_seleccionados = array();

        foreach ($all_permissions as $key => $permission) {
            $permisos_seleccionados[$key] = $permission->id;
        }

        $usuario->permisos_seleccionados = $permisos_seleccionados;

        return Inertia::render('Asignar/NuevoMostrar', compact('usuario', 'roles', 'permissions'));
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
    public function update(Request $request, Asignaciones $asignacion)
    {
        try {
            $tipoComprobante = $request->nombre;
            $tipoComprobante->update();
            $result = ['successMessage' => 'Comprobante actualizado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el comprobante'];
        }

        return redirect()->route('usuarios.asignar')->with($result);
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
