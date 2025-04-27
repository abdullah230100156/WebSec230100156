<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\ProductsController;
use App\Http\Controllers\Web\UsersController;
use App\Http\Controllers\Web\PurchaseController;


Route::get('register', [UsersController::class, 'register'])->name('register');
Route::post('register', [UsersController::class, 'doRegister'])->name('do_register');
Route::get('login', [UsersController::class, 'login'])->name('login');
Route::post('login', [UsersController::class, 'doLogin'])->name('do_login');
Route::get('logout', [UsersController::class, 'doLogout'])->name('do_logout');
Route::get('users', [UsersController::class, 'list'])->name('users');
Route::get('profile/{user?}', [UsersController::class, 'profile'])->name('profile');
Route::get('users/edit/{user?}', [UsersController::class, 'edit'])->name('users_edit');
Route::post('users/save/{user}', [UsersController::class, 'save'])->name('users_save');
Route::get('users/delete/{user}', [UsersController::class, 'delete'])->name('users_delete');
Route::get('users/edit_password/{user?}', [UsersController::class, 'editPassword'])->name('edit_password');
Route::post('users/save_password/{user}', [UsersController::class, 'savePassword'])->name('save_password');



Route::get('products', [ProductsController::class, 'list'])->name('products_list');
Route::get('products/edit/{product?}', [ProductsController::class, 'edit'])->name('products_edit');
Route::post('products/save/{product?}', [ProductsController::class, 'save'])->name('products_save');
Route::get('products/delete/{product}', [ProductsController::class, 'delete'])->name('products_delete');

Route::get('/', function () {
    return view('welcome');
});

Route::get('/multable', function (Request $request) {
    $j = $request->number??5;
    $msg = $request->msg;
    return view('multable', compact("j", "msg"));
});

Route::get('/even', function () {
    return view('even');
});

Route::get('/prime', function () {
    return view('prime');
});

Route::get('/test', function () {
    return view('test');
});
Route::post('/purchase/{id}', [ProductsController::class, 'purchase'])->name('product.purchase');
Route::get('/my-purchases', [PurchaseController::class, 'myPurchases'])->name('my.purchases');


Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [UsersController::class, 'profile'])->name('profile');
});


Route::middleware('auth')->group(function () {
    Route::get('/purchases', [PurchaseController::class, 'myPurchases'])->name('my.purchases');
    Route::post('/purchase/{productId}', [PurchaseController::class, 'purchaseProduct'])->name('purchase.product');
});

Route::post('/product/{id}/purchase', [PurchaseController::class, 'purchaseProduct'])->name('product.purchase');

Route::get('/users/create', [UsersController::class, 'create'])->name('users_create');
Route::post('/users/store', [UsersController::class, 'store'])->name('users_store');

Route::get('/users/add-credit', [\App\Http\Controllers\Web\UsersController::class, 'addCreditForm'])->name('credit.form');
Route::post('/users/add-credit', [\App\Http\Controllers\Web\UsersController::class, 'storeCredit'])->name('credit.store');


Route::delete('/users/{id}', [UsersController::class, 'destroy'])->name('users_delete');

Route::get('/verify', [UsersController::class, 'verify'])->name('verify');
