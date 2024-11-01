<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        $userId = Auth::id(); // Kullanıcının oturum açma durumunu kontrol et

        // Kullanıcı oturum açmadıysa, kayıt sayfasına yönlendir
        if (!$userId) {
            return redirect()->route('register')
                ->with('warning', 'Alışveriş yapabilmek için kayıt olmanız gerekli.');
        }

        // Kullanıcının sepetini bul veya oluştur
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Ürün zaten sepette var mı kontrol et
        $cartItem = CartItem::where('cart_id', $cart->id)
                             ->where('product_id', $id)
                             ->first();

        if ($cartItem) {
            // Ürün zaten varsa, miktarı artır
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            // Yeni bir sepet öğesi oluştur
            CartItem::create([
                'cart_id' => $cart->id,
                'product_id' => $id,
                'quantity' => $quantity,
            ]);
        }

        return $request->input('buy_now') 
            ? redirect()->route('cart.view')->with('success', 'Product added to cart! Proceed to checkout.')
            : redirect()->back()->with('success', 'Product added to cart!');
    }

    public function removeFromCart($id)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();
            if ($cartItem) {
                $cartItem->delete(); // Sepet öğesini sil
            }
        }

        return redirect()->back()->with('success', 'Product removed from cart!');
    }

    public function viewCart()
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->with('cartItems.product')->first();

        // Sepet mevcut mu kontrol et
        if (!$cart || $cart->cartItems->isEmpty()) {
            return view('frontend.cart', ['cart' => null, 'totalPrice' => 0])
                ->with('warning', 'Your cart is empty.');
        }

        // Toplam fiyatı hesapla
        $totalPrice = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('frontend.cart', compact('cart', 'totalPrice'));
    }
}
