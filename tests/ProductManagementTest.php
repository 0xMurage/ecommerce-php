<?php

use App\Models\User;

class ProductManagementTest extends TestCase
{


    public function testShouldCreateProduct()
    {
        $impersonationUser = User::whereHas('roles', function ($query) {
            return $query->where('name', 'admin');
        })->firstOrFail();


        $newProduct = [
            "name" => "Test product",
            "description" => "Demo product",
            "type" => "test",
            "category" => "cat 2",
            "manufacturer" => "microsoft",
            "distributor" => "Demo Logistics",
            "quantity" => 200,
            "unit_cost" => 0
        ];

        $this->actingAs($impersonationUser)->post('/products/new', $newProduct)
            ->seeStatusCode(201)
            ->seeJson(['message' => 'Product created successfully.']);

        $this->assertTrue($this->response['product']['type'] == $newProduct['type']);
    }


}
