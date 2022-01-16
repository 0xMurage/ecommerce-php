<?php

namespace App\Policies;

use App\Models\User;

class ProductPolicy
{

    public function view(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('view_product');
    }

    public function create(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('add_product');
    }

    public function update(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('edit_product');
    }

    public function delete(User $user)
    {
        return $user->roles->pluck('permissions.*.name')->flatten()->contains('delete_product');
    }
}
