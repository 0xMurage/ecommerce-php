<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class ProductService
{

    /**
     * @return Product[]
     */
    public function search($price, $name, $filterByUser = false)
    {
        if ($filterByUser) {
            #only search my products
            return Product::where('user_id', Auth::id())
                ->where((function ($query) use ($name, $price) { //logical grouping
                    $query->when($price, function ($query) use ($price) {
                        $query->whereRaw('quantity*unit_cost=?', [$price]);
                    })->when($name, function ($query) use ($name) {
                        $query->orWhere('name', 'like', '%' . $name . '%');
                    });
                }))->get();
        }

        return Product::when($price, function ($query) use ($price) {
            $query->whereRaw('quantity*unit_cost=?', [$price]);
        })->When($name, function ($query) use ($name) {
            $query->orWhere('name', 'like', '%' . $name . '%');
        })->get();
    }
}
