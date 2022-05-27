<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaypalController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\SubOrderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::redirect('/', '/home');

Auth::routes();

Route::get('/home', [HomeController::class, 'index'])->name('home');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');


Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::resource('products', ProductController::class);

Route::get('/add-to-cart/{product}', [CartController::class, 'add'])->name('cart.add')->middleware('auth');
Route::get('/cart', [CartController::class, 'index'])->name('cart.index')->middleware('auth');
Route::get('/cart/destroy/{itemId}', [CartController::class, 'destroy'])->name('cart.destroy')->middleware('auth');
Route::get('/cart/update/{itemId}', [CartController::class, 'update'])->name('cart.update')->middleware('auth');
Route::get('/cart/checkout', [CartController::class, 'checkout'])->name('cart.checkout')->middleware('auth');
Route::get('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.coupon')->middleware('auth');

Route::resource('orders', OrderController::class)->only('store')->middleware('auth');

Route::resource('shops',ShopController::class)->middleware('auth');


Route::get('paypal/checkout/{order}', [PayPalController::class, 'getExpressCheckout'])->name('paypal.checkout');
Route::get('paypal/checkout-success/{order}', [PayPalController::class, 'getExpressCheckoutSuccess'])->name('paypal.success');
Route::get('paypal/checkout-cancel', [PayPalController::class, 'cancelPage'])->name('paypal.cancel');


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
    Route::get('/order/pay/{suborder}', [SubOrderController::class, 'pay'])->name('order.pay');
});


Route::group(['prefix' => 'seller', 'middleware' => 'auth', 'as' => 'seller.', 'namespace' => 'Seller'], function () {

    Route::redirect('/', 'seller/orders');

    Route::resource('/orders',  OrderController::class);

    Route::get('/orders/delivered/{suborder}',  [OrderController::class, 'markDelivered'])->name('order.delivered');
});
