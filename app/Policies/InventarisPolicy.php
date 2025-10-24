<?php

namespace App\Policies;

use App\Models\Inventaris;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization; // Pastikan ini ada

class InventarisPolicy
{
    use HandlesAuthorization; // Tambahkan trait ini

    /**
     * Determine whether the user can view any models.
     * Semua user bisa lihat daftar inventaris
     */
    public function viewAny(User $user): bool
    {
        return true; // Atau sesuaikan jika perlu batasan
    }

    /**
     * Determine whether the user can view the model.
     * Semua user bisa lihat detail inventaris
     */
    public function view(User $user, Inventaris $inventaris): bool
    {
        return true; // Atau sesuaikan jika perlu batasan
    }

    /**
     * Determine whether the user can create models.
     * Hanya admin dan staff yang boleh membuat
     */
    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can update the model.
     * Hanya admin dan staff yang boleh mengupdate
     */
    public function update(User $user, Inventaris $inventaris): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can delete the model.
     * Hanya admin yang boleh menghapus
     */
    public function delete(User $user, Inventaris $inventaris): bool
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     * (Jika menggunakan soft delete)
     */
    // public function restore(User $user, Inventaris $inventaris): bool
    // {
    //     return $user->role === 'admin';
    // }

    /**
     * Determine whether the user can permanently delete the model.
     * (Jika menggunakan soft delete)
     */
    // public function forceDelete(User $user, Inventaris $inventaris): bool
    // {
    //     return $user->role === 'admin';
    // }

    /**
     * Determine whether the user can import data.
     * Hanya admin dan staff
     */
    public function import(User $user): bool
    {
         return in_array($user->role, ['admin', 'staff']);
    }

    /**
     * Determine whether the user can export data.
     * Semua user boleh
     */
    public function export(User $user): bool
    {
         return true;
    }
     /**
     * Determine whether the user can print data.
     * Semua user boleh
     */
    public function print(User $user): bool
    {
         return true;
    }
}
