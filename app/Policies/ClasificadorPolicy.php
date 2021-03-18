<?php

namespace App\Policies;

use App\Models\Clasificador;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ClasificadorPolicy
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
        return $user->can('Listar Clasificadores');
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clasificador  $clasificador
     * @return mixed
     */
    public function view(User $user, Clasificador $clasificador)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clasificador  $clasificador
     * @return mixed
     */
    public function update(User $user, Clasificador $clasificador)
    {
        //
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clasificador  $clasificador
     * @return mixed
     */
    public function delete(User $user, Clasificador $clasificador)
    {
        //
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clasificador  $clasificador
     * @return mixed
     */
    public function restore(User $user, Clasificador $clasificador)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Clasificador  $clasificador
     * @return mixed
     */
    public function forceDelete(User $user, Clasificador $clasificador)
    {
        //
    }
}
