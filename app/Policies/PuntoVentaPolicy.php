<?php

namespace App\Policies;

use App\Models\PuntosVenta;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class PuntoVentaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        return $user->can('Listar Puntos-Venta');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PuntosVenta  $puntosVenta
     * @return mixed
     */
    public function view(User $user, PuntosVenta $puntosVenta)
    {
        return $user->can('Mostrar Puntos-Venta');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Crear Puntos-Venta');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PuntosVenta  $puntosVenta
     * @return mixed
     */
    public function update(User $user, PuntosVenta $puntosVenta)
    {
        return $user->can('Editar Puntos-Venta');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PuntosVenta  $puntosVenta
     * @return mixed
     */
    public function delete(User $user, PuntosVenta $puntosVenta)
    {
        return $user->can('Eliminar Puntos-Venta');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PuntosVenta  $puntosVenta
     * @return mixed
     */
    public function restore(User $user, PuntosVenta $puntosVenta)
    {
        return $user->can('Restaurar Puntos-Venta');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\PuntosVenta  $puntosVenta
     * @return mixed
     */
    public function forceDelete(User $user, PuntosVenta $puntosVenta)
    {
        //
    }
}
