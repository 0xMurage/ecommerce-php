<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{

    /**
     * Run the product table seeder.
     *
     * @return void
     */
    public function run()
    {
        $users=User::all();

        Product::factory()
            ->count(10)
            ->state(new Sequence(function ($sequence) use ($users){
                return ['user_id' => $users->random()];
            }
            ))
            ->create();
    }
}
