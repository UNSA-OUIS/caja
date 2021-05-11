<?php

namespace App\Policies;

use App\Models\UnidadMedida;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnidadMedidaPolicy
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
        return $user->can('Listar Unidades-Medida');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UnidadMedida  $unidadMedida
     * @return mixed
     */
    public function view(User $user, UnidadMedida $unidadMedida)
    {
        $permiso = false;

        if ($user->can('Mostrar Unidades-Medida')) {
            $permiso = true;
        }
        else if ($user->can('Mostrar Unidades-Medida Propios')) {
            $permiso = $user->id === $unidadMedida->user_id;
        }

        return $permiso;
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->can('Crear Unidades-Medida');   
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UnidadMedida  $unidadMedida
     * @return mixed
     */
    public function update(User $user, UnidadMedida $unidadMedida)
    {
        $permiso = false;

        if ($user->can('Editar Unidades-Medida')) {
            $permiso = true;
        }
        else if ($user->can('Editar Unidades-Medida Propios')) {
            $permiso = $user->id === $unidadMedida->user_id;
        }

        return $permiso;
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UnidadMedida  $unidadMedida
     * @return mixed
     */
    public function delete(User $user, UnidadMedida $unidadMedida)
    {
        $permiso = false;

        if ($user->can('Eliminar Unidades-Medida')) {
            $permiso = true;
        }
        else if ($user->can('Eliminar Unidades-Medida Propios')) {
            $permiso = $user->id === $unidadMedida->user_id;
        }

        return $permiso;              
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UnidadMedida  $unidadMedida
     * @return mixed
     */
    public function restore(User $user, UnidadMedida $unidadMedida)
    {
        $permiso = false;

        if ($user->can('Restaurar Unidades-Medida')) {
            $permiso = true;
        }
        else if ($user->can('Restaurar Unidades-Medida Propios')) {
            $permiso = $user->id === $unidadMedida->user_id;
        }

        return $permiso;        
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\UnidadMedida  $unidadMedida
     * @return mixed
     */
    public function forceDelete(User $user, UnidadMedida $unidadMedida)
    {
        //
    }
}
