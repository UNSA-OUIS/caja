<?php

namespace App\Http\Controllers;

use App\Http\Requests\MiUsuarioUpdateRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Rol;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use App\Http\Requests\UsuarioStoreRequest;
use App\Http\Requests\UsuarioUpdateRequest;
use App\Models\Persona;
use Illuminate\Support\Facades\Auth;

class UsuarioController extends Controller
{
    public function index(Request $request)
    {
        //$this->authorize("viewAny", User::class);

        $query = User::where('name', 'like', '%' . $request->filter . '%')
                    ->orWhere('email', 'like', '%' . $request->filter . '%');
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
        $usuario = new User();
        $usuario->name = "";
        $usuario->email = "";
        $usuario->password = "12345678";
        $usuario->roles_seleccionados = array();
        $roles = Rol::select('name as value','name as text')->orderBy('name', 'asc')->get();

        return Inertia::render('Usuarios/NuevoMostrar', compact('usuario', 'roles'));
    }

    public function store(UsuarioStoreRequest $request)
    {
        try {
            $usuario = new User();
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->password = bcrypt($request->password);
            $usuario->syncRoles($request->roles_seleccionados);
            $usuario->save();
            Persona::create([
                'codigo' => 'cajero' . $usuario->id,
                'nombre' => 'CAJERO ' . $usuario->name,
                'user_id' => $usuario->id,
            ]);
            $result = ['successMessage' => 'Usuario registrado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el usuario'];
            \Log::error('UserController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('usuarios.iniciar')->with($result);
    }

    public function show(User $usuario)
    {
        $roles = Rol::select('name as value','name as text')->orderBy('name', 'asc')->get();
        $usuario->roles_seleccionados = $usuario->getRoleNames();

        $permissions = Permission::all();
        $all_permissions = $usuario->getAllPermissions();

        $permisos_seleccionados = array();

        foreach ($all_permissions as $key => $permission) {
            $permisos_seleccionados[$key] = $permission->id;
        }

        $usuario->permisos_seleccionados = $permisos_seleccionados;

        return Inertia::render('Usuarios/NuevoMostrar', compact('usuario', 'roles', 'permissions'));
    }

    public function edit($id)
    {
        //
    }

    public function update(UsuarioUpdateRequest $request, User $usuario)
    {
        try {
            $usuario->name = $request->name;
            $usuario->email = $request->email;
            $usuario->syncRoles($request->roles_seleccionados);
            $usuario->syncPermissions($request->permisos_seleccionados);
            if ($request->password != "") {
                $usuario->password = bcrypt($request->password);
            }
            $usuario->update();
            $result = ['successMessage' => 'Usuario actualizado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el usuario'];
            \Log::error('UsuarioController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('usuarios.iniciar')->with($result);
    }

    public function destroy(User $usuario)
    {
        try {
            $usuario->delete();
            $result = ['successMessage' => 'usuario eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el usuario'];
            \Log::error('UsuarioController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function restore($usuario_id)
    {
        try {
            $usuario = User::withTrashed()->findOrFail($usuario_id);
            $usuario->restore();
            $result = ['successMessage' => 'Usuario restaurado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el usuario'];
            \Log::error('UsuarioController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->back()->with($result);
    }

    public function showMyUser()
    {
        $usuario = Auth::user();
        $usuario->persona = Auth::user()->persona;

        return Inertia::render('Usuarios/Perfil', compact('usuario'));
    }

    public function editMyUser(MiUsuarioUpdateRequest $request, User $usuario)
    {
        try {                       
            $usuario->persona->id = $request->id;
            $usuario->persona->celular = $request->celular;
            $usuario->persona->email_personal = $request->email_personal;
            $usuario->persona->direccion = $request->direccion;
            $usuario->persona->nombre = $request->nombre;
            $usuario->persona->codigo = $request->codigo;
            $usuario->persona->update();

            if ($request->password != "") {
                $usuario->password = bcrypt($request->password);
                $usuario->update();
                Auth::login($usuario);  
            }
            $result = ['successMessage' => 'Perfil actualizado con éxito'];   

        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el usuario'];
            \Log::error('UsuarioController@editMyUser, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }

        return redirect()->route('usuarios.perfil')->with($result);
    }
}
