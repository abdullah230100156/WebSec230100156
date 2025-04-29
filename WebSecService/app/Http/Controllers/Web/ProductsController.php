<?php

namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Purchase;
use DB;
use App\Http\Controllers\Controller;

class ProductsController extends Controller
{
    use ValidatesRequests;

    // Constructor to apply middleware for authentication, except for the 'list' method
    public function __construct()
    {
        $this->middleware('auth:web')->except('list');
    }

    // Method to list products with filters
    public function list(Request $request)
    {
        $query = Product::select("products.*");

        // Apply filters based on the request parameters
        $query->when($request->keywords, fn($q) => $q->where("name", "like", "%$request->keywords%"));
        $query->when($request->min_price, fn($q) => $q->where("price", ">=", $request->min_price));
        $query->when($request->max_price, fn($q) => $q->where("price", "<=", $request->max_price));
        $query->when($request->order_by, fn($q) => $q->orderBy($request->order_by, $request->order_direction ?? "ASC"));

        // Get filtered products and return the list view
        $products = $query->get();
        return view('products.list', compact('products'));
    }

    // Method to show product editing form or create a new product
    public function edit(Request $request, Product $product = null)
    {
        if (!auth()->user()) return redirect('/');

        // If no product is passed, create a new one
        $product = $product ?? new Product();
        return view('products.edit', compact('product'));
    }

    // Method to save or update a product
    public function save(Request $request, $id = null)
    {
        // Validate required fields, including file upload validation
        $request->validate([
            'code' => 'required|string',
            'model' => 'required|string',
            'name' => 'required|string',
            'price' => 'required|numeric',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Image validation
            'description' => 'required|string',
            'stock' => 'required|numeric|min:0', // Stock validation
        ]);

        // If editing, retrieve the existing product, else create a new product
        $product = $id ? Product::find($id) : new Product();

        // Assign data to the product model
        $product->code = $request->code;
        $product->model = $request->model;
        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->in_stock = $request->stock;

        // Handle photo upload if exists
        if ($request->hasFile('photo')) {
            $imageName = time() . '.' . $request->photo->extension();
            $request->photo->storeAs('public/products', $imageName);  // Store image in 'public/products' folder
            $product->photo = 'products/' . $imageName;  // Save the relative image path
        }

        // Save the product to the database
        $product->save();

        // Redirect with a success message
        return redirect()->route('products_list')->with('success', 'Product saved successfully!');
    }

    // Method to delete a product
    public function delete(Request $request, Product $product)
    {
        // Check if the user has permission to delete the product
        if (!auth()->user()->hasPermissionTo('delete_products')) {
            abort(401);
        }

        // Delete the product
        $product->delete();
        return redirect()->route('products_list');
    }

    // Method to handle product purchase
    public function purchase(Request $request, $productId)
    {
        $user = auth()->user();
        $product = Product::findOrFail($productId);

        // Check if the user has enough credit to purchase the product
        if ($user->credit >= $product->price) {
            // Deduct the price from the user's credit
            $user->credit -= $product->price;
            $user->save();

            // Create a purchase record
            Purchase::create([
                'user_id' => $user->id,
                'product_id' => $product->id,
                'price' => $product->price,
            ]);

            // Redirect with a success message
            return redirect()->back()->with('success', 'Product purchased successfully!');
        } else {
            // Redirect with an error message if not enough credit
            return redirect()->back()->with('error', 'Not enough credit!');
        }
    }
}
