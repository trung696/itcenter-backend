<?php

namespace App\Policies;

use App\DoiLop;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class KhuyenMaiPolicy
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
     * @param  \App\DoiLop  $doiLop
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->checkPermissionAccess('khuyen_mai_list');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->checkPermissionAccess('khuyen_mai_add');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\DoiLop  $doiLop
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->checkPermissionAccess('khuyen_mai_edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DoiLop  $doiLop
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->checkPermissionAccess('khuyen_mai_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\DoiLop  $doiLop
     * @return mixed
     */
    public function restore(User $user)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DoiLop  $doiLop
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
