<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReportePolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can see the report.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function cajero(User $user)
    {
        return $user->can('Cajero Reportes-Periodo');   
    }

    public function descuento(User $user)
    {
        return $user->can('Descuento Reportes-Periodo');   
    }
    
    public function centroCosto(User $user)
    {
        return $user->can('Centro-Costo Reportes-Periodo');   
    }

    public function reciboIngreso(User $user)
    {
        return $user->can('Recibo-Ingreso Reportes-Periodo');   
    }

    public function consolidado(User $user)
    {
        return $user->can('Consolidado Reportes-Periodo');   
    }

    public function facturas(User $user)
    {
        return $user->can('Facturas Reportes-Periodo');   
    }

    public function notas(User $user)
    {
        return $user->can('Notas Reportes-Periodo');   
    }
}
