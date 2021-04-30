<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Empresa;
use Illuminate\Http\Request;
use App\Http\Requests\EmpresaStoreRequest;
use App\Http\Requests\EmpresaUpdateRequest;

class EmpresaController extends Controller
{
    public function index(Request $request)
    {
        $query = Empresa::where('ruc', 'like', '%' . $request->filter . '%')
                    ->orWhere('razon_social', 'like', '%' . $request->filter . '%')
                    ->orWhere('direccion', 'like', '%' . $request->filter . '%') 
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
        $empresa = new Empresa();

        $empresa->ruc = "";
        $empresa->razon_social = "";
        $empresa->direccion = "";        
        $empresa->email = "";                           

        return Inertia::render('Empresas/NuevoMostrar', compact('empresa'));
    }

    public function store(EmpresaStoreRequest $request)
    {
        try {           
            $empresa = new Empresa();
            $empresa->ruc = $request->ruc;
            $empresa->razon_social = $request->razon_social;
            $empresa->direccion = $request->direccion;
            $empresa->email = $request->email;            
            $empresa->save();
            $result = ['successMessage' => 'Empresa registrada con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar la empresa'];
            \Log::error('EmpresaController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }   

        return redirect()->route('empresas.iniciar')->with($result);
    }

    public function show(Empresa $empresa)
    {
        return Inertia::render('Empresas/NuevoMostrar', compact('empresa'));
    }

    public function edit($id)
    {
        //
    }

    public function update(EmpresaUpdateRequest $request, Empresa $empresa)
    {
        try {           
            $empresa->ruc = $request->ruc;            
            $empresa->razon_social = $request->razon_social;
            $empresa->direccion = $request->direccion;
            $empresa->email = $request->email;                    
            $empresa->update();
            $result = ['successMessage' => 'Empresa actualizada con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar la empreaa'];
            \Log::error('EmpreaaController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('empresas.iniciar')->with($result);        
    }

    public function destroy(Empresa $empresa)
    {
        try {                       
            $empresa->delete();            
            $result = ['successMessage' => 'Empresa eliminada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar la empresa'];
            \Log::error('EmpresaController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);
    }

    public function restore($empresa_id) 
    {       
        try {                       
            $empresa = Empresa::withTrashed()->findOrFail($empresa_id);  
            $empresa->restore();
            $result = ['successMessage' => 'Empresa restaurada con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar la empresa'];
            \Log::error('EmpresaController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);
    }
}
