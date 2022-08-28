<?php

namespace App\Policies;

use App\HoanTien;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HoanTienPolicy
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
     * @param  \App\HoanTien  $hoanTien
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->checkPermissionAccess('teacher_list');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\HoanTien  $hoanTien
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->checkPermissionAccess('hoan_tien_list');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\HoanTien  $hoanTien
     * @return mixed
     */
    public function delete(User $user)
    {
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\HoanTien  $hoanTien
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
     * @param  \App\HoanTien  $hoanTien
     * @return mixed
     */
    public function forceDelete(User $user)
    {
        //
    }
}
