<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{

    public function index()
    {

        if (Auth::user()->cannot('view', Product::class)) {
            //if user has no "view all" products permissions
            return response()->json(["message" => "Your products",
                'products' => Product::where('user_id', Auth::id())->get()]);
        } else {

            return response()->json(["message" => "All products",
                'products' => Product::with('author:id,first_name,last_name')->get()]);
        }
    }


    public function store(Request $request)
    {
        $this->authorize('create', Product::class);


        //validate the request data
        $validated = $this->validate($request, [
            'name' => ['required', 'min:2', 'max:255'],
            'description' => ['sometimes', 'max:200'],
            'type' => ['required', 'string', 'max:100'],
            'category' => ['required', 'string', 'max:100'],
            'manufacturer' => ['sometimes', 'string', 'max:255'],
            'distributor' => ['sometimes', 'string', 'max:255'],
            'quantity' => ['required', 'numeric', 'min:0'],
            'unit_cost' => ['required', 'numeric', 'min:0']
        ]);

        //create the role and then attach the permissions
        DB::beginTransaction();
        $product = new product();
        $product->name = $request->get('name');
        $product->description = $request->get('description');
        $product->type = $request->get('type');
        $product->category = $request->get('category');
        $product->manufacturer = $request->get('manufacturer');
        $product->distributor = $request->get('distributor');
        $product->quantity = $request->get('quantity');
        $product->unit_cost = $request->get('unit_cost');
        $product->user_id = Auth::id();
        $product->save();
        DB::commit();

        return response()
            ->json(['message' => 'Product created successfully.',
                'product' => $product], 201);
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
