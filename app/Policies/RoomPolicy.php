<?php

namespace App\Policies;

use App\Models\Room;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RoomPolicy
{
    use HandlesAuthorization;

    // Admin bisa melakukan segalanya
    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null; // Lanjutkan ke method di bawah
    }

    /**
     * Dosen dan Staff bisa melihat daftar
     */
    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'dosen']);
    }

    /**
     * Dosen dan Staff bisa melihat detail
     */
    public function view(User $user, Room $room): bool
    {
        return in_array($user->role, ['admin', 'staff', 'dosen']);
    }

    /**
     * Hanya staff (dan admin) yang bisa membuat
     */
    public function create(User $user): bool
    {
        return $user->role === 'staff'; // Admin sudah ditangani 'before'
    }

    /**
     * Hanya staff (dan admin) yang bisa update
     */
    public function update(User $user, Room $room): bool
    {
        return $user->role === 'staff';
    }

    /**
     * Hanya staff (dan admin) yang bisa delete
     */
    public function delete(User $user, Room $room): bool
    {
        return $user->role === 'staff';
    }
}
