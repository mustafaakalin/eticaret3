<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CartController extends Controller
{
    public function addToCart(Request $request, $id)
    {
        $product = Product::findOrFail($id);
        $quantity = $request->input('quantity', 1);
        $userId = Auth::id();

        // Kullanıcı oturum açmadıysa, kayıt sayfasına yönlendir
        if (!$userId) {
            return redirect()->route('register')->with('warning', 'Alışveriş yapabilmek için kayıt olmanız gerekli.');
        }

        // Kullanıcının sepetini bul veya oluştur
        $cart = Cart::firstOrCreate(['user_id' => $userId]);

        // Ürün zaten sepette var mı kontrol et
        $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();

        // Ürün miktarını kontrol et
        if ($quantity <= 0) {
            return redirect()->back()->with('error', 'Geçersiz miktar!');
        }

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
            ? redirect()->route('cart.view')->with('success', 'Ürün sepete eklendi! Ödeme sayfasına geçin.')
            : redirect()->back()->with('success', 'Ürün sepete eklendi!');
    }

    public function updateCart(Request $request, $id)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();
            if ($cartItem) {
                // Formdan gelen isteği doğrulama
                $validator = Validator::make($request->all(), [
                    'action' => 'required|in:increase,decrease',
                ]);

                if ($validator->fails()) {
                    return redirect()->back()->withErrors($validator)->withInput();
                }

                $action = $request->input('action');

                if ($action === 'increase') {
                    $cartItem->quantity++; // Miktarı artır
                    $cartItem->save(); // Değişiklikleri kaydet
                    return redirect()->route('cart.view')->with('success', 'Ürün miktarı başarıyla artırıldı!');
                } elseif ($action === 'decrease' && $cartItem->quantity > 1) {
                    $cartItem->quantity--; // Miktarı azalt
                    $cartItem->save(); // Değişiklikleri kaydet
                    return redirect()->route('cart.view')->with('success', 'Ürün miktarı başarıyla azaltıldı!');
                }
            }
        }

        return redirect()->back()->with('error', 'Sepet güncelleme işlemi başarısız oldu!');
    }

    public function removeFromCart($id)
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->first();

        if ($cart) {
            $cartItem = CartItem::where('cart_id', $cart->id)->where('product_id', $id)->first();
            if ($cartItem) {
                $cartItem->delete(); // Sepet öğesini sil
                return redirect()->back()->with('success', 'Ürün sepetten kaldırıldı!');
            }
        }

        return redirect()->back()->with('error', 'Ürün sepetten kaldırma işlemi başarısız oldu!');
    }

    public function viewCart()
    {
        $userId = Auth::id();
        $cart = Cart::where('user_id', $userId)->with('cartItems.product')->first();

        // Sepet mevcut mu kontrol et
        if (!$cart || $cart->cartItems->isEmpty()) {
            return view('frontend.cart', ['cart' => null, 'totalPrice' => 0])
                ->with('warning', 'Sepetiniz boş.');
        }

        // Toplam fiyatı hesapla
        $totalPrice = $cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        });

        return view('frontend.cart', compact('cart', 'totalPrice'));
    }
}
