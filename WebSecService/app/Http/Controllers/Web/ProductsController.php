<?php

namespace App\Http\Controllers\Web;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use App\Models\Purchase;
use DB;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductsController extends Controller
{

	use ValidatesRequests;

	public function __construct()
	{
		$this->middleware('auth:web')->except('list');
	}

	public function list(Request $request)
	{

		$query = Product::select("products.*");

		$query->when(
			$request->keywords,
			fn($q) => $q->where("name", "like", "%$request->keywords%")
		);

		$query->when(
			$request->min_price,
			fn($q) => $q->where("price", ">=", $request->min_price)
		);

		$query->when($request->max_price, fn($q) =>
		$q->where("price", "<=", $request->max_price));

		$query->when(
			$request->order_by,
			fn($q) => $q->orderBy($request->order_by, $request->order_direction ?? "ASC")
		);

		$products = $query->get();

		return view('products.list', compact('products'));
	}

	public function edit(Request $request, Product $product = null)
	{

		if (!auth()->user()) return redirect('/');

		$product = $product ?? new Product();

		return view('products.edit', compact('product'));
	}

	public function save(Request $request, $id = null)
{
    // Validation for required fields, including file upload.
    $request->validate([
        'code' => 'required|string',
        'model' => 'required|string',
        'name' => 'required|string',
        'price' => 'required|numeric',
        'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validating image
        'description' => 'required|string',
        'stock' => 'required|numeric|min:0', // Add stock validation
    ]);

    // If editing, retrieve the product, else create a new product.
    $product = $id ? Product::find($id) : new Product();

    // Assign data to the product model
    $product->code = $request->code;
    $product->model = $request->model;
    $product->name = $request->name;
    $product->price = $request->price;
    $product->description = $request->description;
    $product->in_stock = $request->stock;

    // Handle photo upload
    if ($request->hasFile('photo')) {
        $imageName = time() . '.' . $request->photo->extension();
        $request->photo->storeAs('public/products', $imageName);  // Store the photo in the 'public/products' folder
        $product->photo = 'products/' . $imageName;  // Save the relative path
    }

    // Save the product to the database
    $product->save();

    // Redirect with a success message
    return redirect()->route('products_list')->with('success', 'Product saved successfully!');
}

	public function delete(Request $request, Product $product)
	{

		if (!auth()->user()->hasPermissionTo('delete_products')) abort(401);

		$product->delete();

		return redirect()->route('products_list');
	}
	public function purchase(Request $request, $productId)
	{
		$user = auth()->user();
		$product = Product::findOrFail($productId);

		if ($user->credit >= $product->price) {
			$user->credit -= $product->price;
			$user->save();

			Purchase::create([
				'user_id' => $user->id,
				'product_id' => $product->id,
				'price' => $product->price,
			]);

			return redirect()->back()->with('success', 'Product purchased successfully!');
		} else {
			return redirect()->back()->with('error', 'Not enough credit!');
		}
	}

	
}
