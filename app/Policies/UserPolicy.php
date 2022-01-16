<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{

    public function view(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('view_user');
    }

    public function create(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('add_user');
    }

    public function update(User $user, User $accountToUpdate)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('edit_user');
    }

    public function delete(User $user, User $accountToDelete)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('delete_user');
    }
}
