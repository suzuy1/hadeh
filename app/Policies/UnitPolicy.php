<?php

namespace App\Policies;

use App\Models\Unit;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UnitPolicy
{
    use HandlesAuthorization;

    public function before(User $user, string $ability): bool|null
    {
        if ($user->role === 'admin') {
            return true;
        }
        return null;
    }

    public function viewAny(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'dosen']);
    }

    public function view(User $user, Unit $unit): bool
    {
        return in_array($user->role, ['admin', 'staff', 'dosen']);
    }

    public function create(User $user): bool
    {
        return $user->role === 'staff';
    }

    public function update(User $user, Unit $unit): bool
    {
        return $user->role === 'staff';
    }

    public function delete(User $user, Unit $unit): bool
    {
        return $user->role === 'staff';
    }
}
