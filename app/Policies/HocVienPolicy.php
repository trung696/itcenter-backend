<?php

namespace App\Policies;

use App\HocVien;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class HocVienPolicy
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
     * @param  \App\HocVien  $hocVien
     * @return mixed
     */
    public function view(User $user)
    {
        return $user->checkPermissionAccess('student_list');
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->checkPermissionAccess('student_add');
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\HocVien  $hocVien
     * @return mixed
     */
    public function update(User $user)
    {
        return $user->checkPermissionAccess('student_edit');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\HocVien  $hocVien
     * @return mixed
     */
    public function delete(User $user)
    {
        return $user->checkPermissionAccess('student_delete');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\User  $user
     * @param  \App\HocVien  $hocVien
     * @return mixed
     */
    public function restore(User $user, HocVien $hocVien)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\HocVien  $hocVien
     * @return mixed
     */
    public function forceDelete(User $user, HocVien $hocVien)
    {
        //
    }
}
