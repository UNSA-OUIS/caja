<?php

namespace App\Providers;

use App\Models\User;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        // 'App\Models\Model' => 'App\Policies\ModelPolicy',
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('cajero', function (User $user) {
            return $user->can('Cajero Reportes-Periodo');
        });
        Gate::define('descuento', function (User $user) {
            return $user->can('Descuento Reportes-Periodo');
        });
        Gate::define('centroCosto', function (User $user) {
            return $user->can('Centro-Costo Reportes-Periodo');
        });
        Gate::define('reciboIngreso', function (User $user) {
            return $user->can('Recibo-Ingreso Reportes-Periodo');
        });
        Gate::define('consolidado', function (User $user) {
            return $user->can('Consolidado Reportes-Periodo');
        });
        Gate::define('facturas', function (User $user) {
            return $user->can('Facturas Reportes-Periodo');
        });
        Gate::define('notas', function (User $user) {
            return $user->can('Notas Reportes-Periodo');
        });
    }
}
