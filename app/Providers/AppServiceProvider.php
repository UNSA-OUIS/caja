<?php

namespace App\Providers;

use Inertia\Inertia;
use Illuminate\Support\ServiceProvider;
use Illuminate\Database\Eloquent\Relations\Relation;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Relation::morphMap([
            'alumno' => 'App\Models\Alumno',            
            'docente' => 'App\Models\Docente',            
            'dependencia' => 'App\Models\Dependencia',            
            'particular' => 'App\Models\Particular',            
            'empresa' => 'App\Models\Empresa',            
        ]);
    }
}
