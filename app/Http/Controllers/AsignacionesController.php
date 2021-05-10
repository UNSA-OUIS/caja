<?php

namespace App\Http\Controllers;

use App\Models\Asignaciones;
use App\Models\Rol;
use App\Models\User;
use Illuminate\Http\Request;
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
        $asignacion = new Asignaciones();
        $asignacion->serie = "";
        $asignacion->correlativo = "";
        $asignacion->tipo_comprobante_id = "";
        $asignacion->user_id = "";

        $roles = Rol::select('name as value', 'name as text')->orderBy('name', 'asc')->get();
        $usuario->roles_seleccionados = $usuario->getRoleNames();

        $permissions = Permission::all();
        $all_permissions = $usuario->getAllPermissions();

        $permisos_seleccionados = array();

        foreach ($all_permissions as $key => $permission) {
            $permisos_seleccionados[$key] = $permission->id;
        }

        $usuario->permisos_seleccionados = $permisos_seleccionados;

        return Inertia::render('Asignar/NuevoMostrar', compact('asignacion', 'usuario', 'roles', 'permissions'));
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
