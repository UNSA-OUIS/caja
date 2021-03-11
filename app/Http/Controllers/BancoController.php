<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Actmail;
use App\Models\Acdiden;
use App\Models\Acdidal;
use App\Models\Banco;
use App\Jobs\SendEmailJob;
use Inertia\Inertia;

class BancoController extends Controller
{ 
    public function index()
    {
        $pagos = Banco::join('actescu', 'banco_bcp.nues', '=', 'actescu.nues')
                            ->select('banco_bcp.nues', 'nesc AS escuela', 'esta_id AS estado', 'insc_codi_web AS codigo',
                                     'fvencimiento AS fecha_vencimiento', 'tipo_pag AS tipo_pago')
                            ->where('cui', \Auth::user()->cui)
                            ->where('usado_unsapay', 1)
                            ->whereIn('tipo_pag', ['MM', 'ME'])
                            ->get();  
        
        return Inertia::render('Historial', compact('pagos'));
    }

    
    public function update(Request $request) 
    {      
        try {
            $actualizado = Banco::where('usado_unsapay', 0)
                        ->where('tipo_pag', $request->tipo_pago['codigo'])
                        ->limit(1)        
                        ->update([
                            'cui' => \Auth::user()->cui,
                            'ndoc' => $request->alumno['dni'],
                            'apn' => $request->alumno['apellidos'] . ', ' . $request->alumno['nombres'],
                            'nues' => $request->matricula['nues'],                            
                            'espe' => $request->matricula['espe'],
                            'usado_unsapay' => 1,
                        ]);           
            
            if ($actualizado) {
                $array_code = \DB::connection('mysql2')->select("SELECT @codigo_web AS codigo");                           
                $codigo = $array_code[0]->codigo;

                //enviar email -> php artisan queue:listen                       
                $details['email'] = \Auth::user()->email;
                $details['codigo'] = $codigo;
                $details['escuela'] = $request->matricula['escuela']['nesc'];
                $details['tipo_pago'] = $request->tipo_pago['nombre'];
                $details['apn'] = $request->alumno['apellidos'] . ', ' . $request->alumno['nombres'];
                $details['cui'] = \Auth::user()->cui;
                $details['dni'] = $request->alumno['dni'];
                dispatch(new SendEmailJob($details));
                
                $result = ['successMessage' => 'Código de pago generado con éxito', 
                            'error' => false,
                            'codigo' => $codigo
                          ];
            }
            else {
                $result = ['warningMessage' => 'Se agotaron los códigos disponibles en el banco', 'error' => true];
            }
        } catch (\Exception $e) {
            $result = ['errorMessage' => 'Se ha producido un error, vuelve a intentarlo más tarde', 'error' => true];
            \Log::warning('BancoController@update, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());           
        }       
                
        return $result;
    }

    public function PDF($codigo, $nues)
    {   
        $cui = \Auth::user()->cui;
        
        $codigo_generado = Banco::where('insc_codi_web', $codigo)                                
                                ->where('usado_unsapay', 1)
                                ->first();

        if ($codigo_generado) {            
            $alumno = Acdiden::select(\DB::raw('(SUBSTRING(acdiden.dic, 2)) AS dni'),
                                  \DB::raw('(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 1)) AS apellidos'),
                                  \DB::raw('(SUBSTRING_INDEX(SUBSTRING_INDEX(REPLACE(acdiden.apn, "/", " "), ",", 2), ",", -1)) AS nombres')
                                )
                            ->where('cui', $cui)
                            ->first();             
        
            $escuela = Acdidal::with('escuela')
                            ->where('cui', $cui)                            
                            ->where('nues', $nues)                            
                            ->first()
                            ->escuela
                            ->nesc;            

            $pdf = \PDF::loadView('pdf.constancia', 
                        [
                            'codigo'  => $codigo,
                            'alumno'  => $alumno,
                            'escuela' => $escuela
                        ]);    
            $pdf->setPaper('A4', 'landscape');               
            
            return $pdf->download('codigo_bancario.pdf');                  
            //return $pdf->stream("codigo_bancario.pdf", array("Attachment" => false));
        }       
        else {
            return view('errors.codigo_invalido', compact('codigo'));
        }
    }
}
