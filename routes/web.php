<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CartController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');


Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('product.show');


Route::post('/products/{product}/comments', [CommentController::class, 'store'])->name('comments.store');





Route::post('/cart/add/{id}', [CartController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');



Route::post('/checkout', [CheckoutController::class, 'process'])->name('checkout.process');


Route::get('/checkout/success', function () {
    return view('frontend.checkout.success');
})->name('checkout.success');

Route::get('/checkout/failure', function () {
    return view('frontend.checkout.failure');
})->name('checkout.failure');



Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [RegisterController::class, 'register']);



// Giriş sayfasını göster
Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');

// Giriş işlemi
Route::post('login', [LoginController::class, 'login']);

// Çıkış işlemi
Route::post('logout', [LoginController::class, 'logout'])->name('logout');
