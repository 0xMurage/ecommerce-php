<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Permissions per service
     * - If I can create a resource, I will be able to view it (even if I don't have view permission)
     * - View permissions allows me to view resources created by others
     * @var \string[][]
     */
    private $permissions = [
        #user resource
        ['name' => 'add_user', 'description' => 'Can create a user account'],
        ['name' => 'edit_user', 'description' => 'Can edit user details'],
        ['name' => 'view_user', 'description' => 'Can view all users'],
        ['name' => 'delete_user', 'description' => 'Can delete user account'],
        #role resource
        ['name' => 'add_role', 'description' => 'Can create a role'],
        ['name' => 'edit_role', 'description' => 'Can edit role details'],
        ['name' => 'view_role', 'description' => 'Can view all roles'],
        ['name' => 'delete_role', 'description' => 'Can delete a role'],
        #product resource
        ['name' => 'add_product', 'description' => 'Can create a product'],
        ['name' => 'edit_product', 'description' => 'Can edit product details'],
        ['name' => 'view_product', 'description' => 'Can view all products'],
        ['name' => 'delete_product', 'description' => 'Can delete a product']
    ];


    /**
     * Run the permission table seeder.
     *
     * @return void
     */
    public function run()
    {
        Permission::insert($this->permissions);
    }

}
