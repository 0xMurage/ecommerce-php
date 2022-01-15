<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class RoleSeeder extends Seeder
{


    /**
     * Run the roles table seeder.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all();

        // 1. seed admin role
        $admin = Role::create(['name' => 'Admin', 'description' => 'Default account administrator']);
        $admin->permissions()->saveMany($permissions);
        //2. Seed customer role
        $customer = Role::create(['name' => 'Customer', 'description' => 'Default customer role']);
        $customer->permissions()->save($permissions->where('name', 'add_product')->first());

        if (!App::environment('production')) {
            #don't create random roles on production

            Role::factory()
                ->count(3)
                ->hasAttached($permissions->random(3),[
                    'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
                ])->create();
        }
    }
}
