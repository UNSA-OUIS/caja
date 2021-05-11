<?php

namespace App\Policies;

use App\Models\Concepto;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ConceptoPolicy
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
        return $user->can('Listar Conceptos');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return mixed
     */
    public function view(User $user, Concepto $concepto)
    {
        $permiso = false;

        if ($user->can('Mostrar Conceptos')) {
            $permiso = true;
        }
        else if ($user->can('Mostrar Conceptos propios')) {
            $permiso = $user->id === $concepto->user_id;
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
        return $user->can('Crear Conceptos');   
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return mixed
     */
    public function update(User $user, Concepto $concepto)
    {
        $permiso = false;

        if ($user->can('Editar Conceptos')) {
            $permiso = true;
        }
        else if ($user->can('Editar Conceptos Propios')) {
            $permiso = $user->id === $concepto->user_id;
        }

        return $permiso;        
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return mixed
     */
    public function delete(User $user, Concepto $concepto)
    {
        $permiso = false;

        if ($user->can('Eliminar Conceptos')) {
            $permiso = true;
        }
        else if ($user->can('Eliminar Conceptos Propios')) {
            $permiso = $user->id === $concepto->user_id;
        }

        return $permiso;              
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return mixed
     */
    public function restore(User $user, Concepto $concepto)
    {
        $permiso = false;

        if ($user->can('Restaurar Conceptos')) {
            $permiso = true;
        }
        else if ($user->can('Restaurar Conceptos Propios')) {
            $permiso = $user->id === $concepto->user_id;
        }

        return $permiso;              
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Concepto  $concepto
     * @return mixed
     */
    public function forceDelete(User $user, Concepto $concepto)
    {
        //
    }
}
