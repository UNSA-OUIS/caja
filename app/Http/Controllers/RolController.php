<?php

namespace App\Http\Controllers;

use App\Models\Rol;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class RolController extends Controller
{
    public function index(Request $request)
    {
        $query = Rol::where('name', 'like', '%' . $request->filter . '%'); 

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
        $rol = new Rol();
        $rol->name = "";

        return Inertia::render('Roles/NuevoMostrar', compact('rol'));
    }

    public function store(Request $request)
    {
        try {           
            $rol = new Rol();
            $rol->name = $request->name;
            $rol->save();
            $result = ['successMessage' => 'Rol registrado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar el rol'];
            \Log::error('RolController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('roles.iniciar')->with($result); 
    }

    public function show(Rol $rol)
    {
        return Inertia::render('Roles/NuevoMostrar', compact('rol'));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, Rol $rol)
    {
        try {                       
            $rol->name = $request->name;
            $rol->update();
            $result = ['successMessage' => 'Rol actualizado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar el rol'];
            \Log::error('RolController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('roles.iniciar')->with($result); 
    }

    public function destroy(Rol $rol)
    {
        try {                       
            $rol->delete();            
            $result = ['successMessage' => 'Rol eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar el rol'];
            \Log::error('RolController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);  
    }

    public function restore($rol_id) 
    {     
        
        try {                       
            $rol = Rol::withTrashed()->findOrFail($rol_id);  
            $rol->restore();
            $result = ['successMessage' => 'Rol restaurado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar el rol'];
            \Log::error('RolController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);            
    }
}
