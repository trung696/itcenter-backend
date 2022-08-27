<?php

namespace App\Policies;

use App\DangKy;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class DangKyPolicy
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
     * @param  \App\DangKy  $dangKy
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->checkPermissionAccess('dangKy_list');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->checkPermissionAccess('dangKy_add');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\DangKy  $dangKy
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->checkPermissionAccess('dangKy_edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DangKy  $dangKy
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->checkPermissionAccess('dangKy_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\DangKy  $dangKy
     * @return mixed
     */
    public function restore(User $user, DangKy $dangKy)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\DangKy  $dangKy
     * @return mixed
     */
    public function forceDelete(User $user, DangKy $dangKy)
    {
        //
    }
}
