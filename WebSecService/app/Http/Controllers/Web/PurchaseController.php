<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Purchase;
use App\Models\Product;

class PurchaseController extends Controller
{
    public function myPurchases()
    {
        $user = auth()->user();
        $purchases = Purchase::with('product')->where('user_id', $user->id)->get();

        return view('purchases.my', compact('purchases'));
    }

    public function purchaseProduct($productId)
    {
        $user = auth()->user();
        $product = Product::findOrFail($productId);
        $productPrice = $product->price;

        // ✅ التحقق من وجود مخزون
    if ($product->in_stock <= 0) {
        return redirect()->back()->with('error', 'This product is out of stock.');
    }

    // ✅ التحقق من الرصيد
    if ($user->credit < $productPrice) {
        return redirect()->back()->with('error', 'Your account does not have enough credit.');
    }

        // خصم الرصيد من المستخدم
        $user->credit -= $productPrice;
        $user->save();

        // خصم الكمية من المخزون
        $product->in_stock -= 1;
        $product->save();

        // تسجيل عملية الشراء
        Purchase::create([
            'user_id' => $user->id,
            'product_id' => $product->id,
            'price' => $productPrice,
        ]);
        

        return redirect()->route('my.purchases')->with('success', 'Purchase successful!');
    }
}
