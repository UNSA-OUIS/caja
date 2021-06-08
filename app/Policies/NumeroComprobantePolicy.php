<?php

namespace App\Policies;

use App\Models\NumeroComprobante;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NumeroComprobantePolicy
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
        return $user->can('Listar Números-Comprobante');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NumeroComprobante  $numeroOperacion
     * @return mixed
     */
    public function view(User $user, NumeroComprobante $numeroOperacion)
    {
        return $user->can('Mostrar Números-Comprobante');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Crear Números-Comprobante');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NumeroComprobante  $numeroOperacion
     * @return mixed
     */
    public function update(User $user, NumeroComprobante $numeroOperacion)
    {
        return $user->can('Editar Números-Comprobante');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NumeroComprobante  $numeroOperacion
     * @return mixed
     */
    public function delete(User $user, NumeroComprobante $numeroOperacion)
    {
        return $user->can('Eliminar Números-Comprobante');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NumeroComprobante  $numeroOperacion
     * @return mixed
     */
    public function restore(User $user, NumeroComprobante $numeroOperacion)
    {
        return $user->can('Restaurar Números-Comprobante');
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\NumeroComprobante  $numeroOperacion
     * @return mixed
     */
    public function forceDelete(User $user, NumeroComprobante $numeroOperacion)
    {
        //
    }
}
