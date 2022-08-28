<?php

namespace App\Policies;

use App\Ca;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ca  $ca
     * @return mixed
     */
    public function view(User $user)
    {
         return $user->checkPermissionAccess('ca_list');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
         return $user->checkPermissionAccess('ca_add');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ca  $ca
     * @return mixed
     */
    public function update(User $user)
    {
         return $user->checkPermissionAccess('ca_edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ca  $ca
     * @return mixed
     */
    public function delete(User $user)
    {
         return $user->checkPermissionAccess('ca_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ca  $ca
     * @return mixed
     */
    public function restore(User $user, Ca $ca)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\Ca  $ca
     * @return mixed
     */
    public function forceDelete(User $user, Ca $ca)
    {
        //
    }
}
