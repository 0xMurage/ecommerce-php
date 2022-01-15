<?php

namespace Database\Factories;

use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Product::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->bothify('Product-#####-?????'),
            'description' => $this->faker->realText(80),
            'type' => $this->faker->word(),
            'category' => $this->faker->randomElement(['Fashion', 'Kitchen Appliances',
                'Vehicles', 'Electronics', 'Health & Beauty', 'Properties']),
            'manufacturer' => $this->faker->company,
            'distributor' => $this->faker->company(),
            'quantity' => $this->faker->numberBetween(1, 400),
            'unit_cost' => $this->faker->numberBetween(0, 5000)
        ];
    }

}
