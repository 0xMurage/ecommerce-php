<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ProductService;
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

        //save the product
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

        return response()
            ->json(['message' => 'Product created successfully.',
                'product' => $product], 201);
    }

    public function update(Request $request, $id)
    {
        $this->authorize('update', Product::class);

        $product = Product::findOrFail($id);

        //validate the request data
        $validated = $this->validate($request, [
            'name' => ['sometimes', 'min:2', 'max:255'],
            'description' => ['sometimes', 'max:200'],
            'type' => ['sometimes', 'string', 'max:100'],
            'category' => ['sometimes', 'string', 'max:100'],
            'manufacturer' => ['sometimes', 'string', 'max:255'],
            'distributor' => ['sometimes', 'string', 'max:255'],
            'quantity' => ['sometimes', 'numeric', 'min:0'],
            'unit_cost' => ['sometimes', 'numeric', 'min:0']
        ]);

        //update the product
        $product->name = $request->get('name', $product->name);
        $product->description = $request->get('description', $product->description);
        $product->type = $request->get('type', $product->type);
        $product->category = $request->get('category', $product->category);
        $product->manufacturer = $request->get('manufacturer', $product->manufacturer);
        $product->distributor = $request->get('distributor', $product->distributor);
        $product->quantity = $request->get('quantity', $product->quantity);
        $product->unit_cost = $request->get('unit_cost', $product->unit_cost);
        $product->user_id = Auth::id();
        $product->update();

        return response()
            ->json(['message' => 'Product updated successfully.',
                'product' => $product]);
    }

    public function destroy($id)
    {
        $this->authorize('delete', Product::class);

        $product = Product::findOrFail($id);
        $product->delete();

        return response()
            ->json(['message' => 'Product deleted successfully.']);

    }

    public function search(Request $request, ProductService $productService)
    {
        //validate the request
        $this->validate($request, [
            'name' => ['required_without_all:price', 'max:255'],
            'price' => ['required_without_all:name', 'numeric']
        ], ['name.required_without_all' => 'Product name to search missing',
                'price.required_without_all' => 'Product price to search missing',]
        );
        if (Auth::user()->cannot('view', Product::class)) {
            //if user has no "view all" products permissions, search only their products
            $results = $productService->search($request->get('price'),
                $request->get('name'), true);
        } else {
            //search all products
            $results = $productService->search($request->get('price'), $request->get('name'));
        }
        return response()
            ->json(['message' => 'Filtered products', 'products' => $results]);
    }

}
