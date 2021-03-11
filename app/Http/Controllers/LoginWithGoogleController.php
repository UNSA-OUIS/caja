<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use App\Models\AccesoGoogle;
use Illuminate\Support\Facades\Auth;
use Exception;

class LoginWithGoogleController extends Controller
{
    public function redirectToGoogle()
    {        
        return Socialite::driver('google')->with(['hd' => 'unsa.edu.pe'])->redirect();
    }

    public function handleGoogleCallback()
    {
        try {      
            $user = Socialite::driver('google')->user();               
                        
            $personal = AccesoGoogle::find($user->email);         
            
            if (!$personal) { // si no es personal de caja
                \Session::flash('errorLoginMessage', "Lo sentimos, esta aplicación es solo para personal con correos registrados en el sistema de caja");
                return redirect()->to('/login');
            }
            
            //$finduser = User::where('google_id', $user->id)->first();
            $finduser = User::where('email', $user->email)->first();
       
            if ($finduser) {       
                Auth::login($finduser);
                return redirect()->intended('dashboard');
       
            } else {           
                
                //dd('jeiken');
                
                $newUser = User::create([                    
                    'name' => $user->name,
                    'email' => $user->email,
                    'google_id' => $user->id,
                    'profile_photo_path' => $user->avatar,                    
                ]);
      
                Auth::login($newUser);
      
                return redirect()->intended('dashboard');
            }
      
        } catch (Exception $e) {
            \Log::warning('LoginWithGoogleController@handleGoogleCallback, Detalle: "'.$e->getMessage().'" on file '.$e->getFile().':'.$e->getLine());
        }
    }   
}
