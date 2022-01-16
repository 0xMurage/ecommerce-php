<?php

namespace App\Policies;

use App\Models\User;

class RolePolicy
{

    public function view(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('view_role');
    }

    public function create(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('add_role');
    }

    public function update(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('edit_role');
    }

    public function delete(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('delete_role');
    }
}
