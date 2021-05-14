<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use App\Models\Particular;
use Illuminate\Http\Request;
use App\Http\Requests\ParticularStoreRequest;
use App\Http\Requests\ParticularUpdateRequest;

class ParticularController extends Controller
{    
    public function index(Request $request)
    {
        $query = Particular::where('dni', 'like', '%' . $request->filter . '%')
                    ->orWhere('nombres', 'like', '%' . $request->filter . '%')
                    ->orWhere('apellidos', 'like', '%' . $request->filter . '%') 
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
        $particular = new Particular();

        $particular->dni = "";
        $particular->nombres = "";
        $particular->apellidos = "";        
        $particular->email = "";                           

        return Inertia::render('Particulares/NuevoMostrar', compact('particular'));
    }

    public function store(ParticularStoreRequest $request)
    {
        try {           
            $particular = new Particular();
            $particular->dni = $request->dni;
            $particular->nombres = $request->nombres;
            $particular->apellidos = $request->apellidos;
            $particular->email = $request->email;            
            $particular->save();
            $result = ['successMessage' => 'Particular registrado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar al particular'];
            \Log::error('ParticularController@store, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }   

        return redirect()->route('particulares.iniciar')->with($result);
    }

    public function show(Particular $particular)
    {
        return Inertia::render('Particulares/NuevoMostrar', compact('particular'));
    }

    public function edit($id)
    {
        //
    }

    public function update(ParticularUpdateRequest $request, Particular $particular)
    {
        try {           
            $particular->dni = $request->dni;            
            $particular->nombres = $request->nombres;
            $particular->apellidos = $request->apellidos;
            $particular->email = $request->email;                    
            $particular->update();
            $result = ['successMessage' => 'Particular actualizado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar al particular'];
            \Log::error('ParticularController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->route('particulares.iniciar')->with($result);        
    }

    public function destroy(Particular $particular)
    {
        try {                       
            $particular->delete();            
            $result = ['successMessage' => 'Particular eliminado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo eliminar al particular'];
            \Log::error('ParticularController@destroy, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);
    }

    public function restore($particular_id) 
    {       
        try {                       
            $particular = Particular::withTrashed()->findOrFail($particular_id);  
            $particular->restore();
            $result = ['successMessage' => 'Particular restaurado con éxito'];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo restaurar al particular'];
            \Log::error('ParticularController@restore, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }                      
        
        return redirect()->back()->with($result);
    }

    public function buscarDniParticular($dni)
    {
        $particular = Particular::where('dni', $dni)->first();

        return json_encode($particular);
    }

    public function buscarApnParticular(Request $request)
    {
        $particulares = Particular::whereRaw("CONCAT(apellidos, ' ', nombres) ilike ? ", [ $request->apn . '%'])
                            ->take(10)->get();

        return $particulares;
    }

    public function registrarParticular(ParticularStoreRequest $request)
    {
        try {
            $particular = new Particular();
            $particular->dni = $request->dni;
            $particular->nombres = $request->nombres;
            $particular->apellidos = $request->apellidos;
            $particular->email = $request->email;
            $particular->save();
            $result = ['successMessage' => 'Particular registrado con éxito', 'error' => false];
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo registrar al particular', 'error' => true];
            \Log::error('ParticularController@registrarParticular, Detalle: "' . $e->getMessage() . '" on file ' . $e->getFile() . ':' . $e->getLine());
        }

        return $result;
    }
    
    public function actualizarParticular(ParticularUpdateRequest $request,Particular $particular){
        try {
            $particular=Particular::where('id', $request->id)->first();
            $particular->nombres = $request->nombres;
            $particular->apellidos = $request->apellidos;
            $particular->email = $request->email;                    
            $particular->update();
            $result = ['successMessage' => 'Particular actualizado con éxito'];            
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'No se pudo actualizar al particular'];
            \Log::error('ParticularController@editarParticular, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }
        return $result;         
    }
}
