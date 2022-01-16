<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{

    public function index()
    {

        if (Auth::user()->cannot('view', Product::class)) {
            //if user has no "view all" products permissions

        } else {

        }
    }


    public function store(Request $request)
    {
        $this->authorize('create', Product::class);

    }

    public function update(Request $request, $id)
    {
        $this->authorize('edit', Product::class);

    }

    public function destroy($id)
    {
        $this->authorize('delete', Product::class);

    }

    public function search(Request $request)
    {

        if (Auth::user()->cannot('view', Product::class)) {
            //if user has no "view all" products permissions, search only their products

        } else {
            //search all products
        }

    }

}
