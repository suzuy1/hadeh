<?php

namespace App\Policies;

use App\Models\Request;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class RequestPolicy
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

    public function view(User $user, Request $request): bool
    {
        return in_array($user->role, ['admin', 'staff', 'dosen']);
    }

    public function create(User $user): bool
    {
        return in_array($user->role, ['admin', 'staff', 'dosen']);
    }

    public function update(User $user, Request $request): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }

    public function delete(User $user, Request $request): bool
    {
        return in_array($user->role, ['admin', 'staff']);
    }
}
