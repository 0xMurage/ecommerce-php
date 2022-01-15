<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{

    /**
     * Run the user table seeder.
     *
     * @return void
     */
    public function run()
    {
        $roles = Role::all();

        User::factory()
            ->count(10)
            ->hasAttached($roles->random(3), [
                'created_at' => Carbon::now(), 'updated_at' => Carbon::now()
            ])->create();

        #ensure we have admin and a customer
        $users = User::limit(2)->get();

        $users[0]->roles()->sync($roles->firstWhere('name', 'Admin'));
        $users[1]->roles()->sync($roles->firstWhere('name', 'Customer'));

    }
}
