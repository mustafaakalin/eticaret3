@extends('frontend.app')

@section('content')

@if (session('message'))
      <div class="alert alert-success" role="alert">
        {{ session('message') }}
      </div>    
@endif
  <!-- Cart Section -->
  <section class="p-10">
    <h1 class="text-4xl font-bold mb-6">Shopping Cart</h1>
    
    @if ($cart && $cart->cartItems->isNotEmpty())
      <div class="overflow-x-auto w-full">
        <table class="table w-full">
          <!-- Head -->
          <thead>
            <tr>
              <th>Product</th>
              <th>Quantity</th>
              <th>Price</th>
              <th>Total</th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            @foreach ($cart->cartItems as $item)
              <tr>
                <td>
                  <div class="flex items-center space-x-3">
                    <div class="avatar">
                      <div class="w-24 rounded">
                        <img src="{{ $item->product->images->isNotEmpty() ? asset('storage/' . $item->product->images->first()->image_path) : asset('default_image.jpg') }}" alt="{{ $item->product->name }}">
                      </div>
                    </div>
                    <div>
                      <div class="font-bold">{{ $item->product->name }}</div>
                    </div>
                  </div>
                </td>
                <td>
                  <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">
                    @csrf
                    <input type="number" name="quantity" value="{{ $item->quantity }}" class="input input-bordered w-20" min="1">
                    <button type="submit" class="btn btn-error btn-sm">Remove</button>
                  </form>
                </td>
                <td>${{ number_format($item->product->price, 2) }}</td>
                <td>${{ number_format($item->product->price * $item->quantity, 2) }}</td>
                <td>
                  <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-error btn-sm">Remove</button>
                  </form>
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Cart Summary -->
      <div class="mt-10 text-right">
        <h2 class="text-2xl font-bold">Subtotal: ${{ number_format($cart->cartItems->sum(function ($item) {
            return $item->product->price * $item->quantity;
        }), 2) }}</h2>
        
        <h3 class="text-lg">Total: ${{ number_format($totalPrice, 2) }}</h3> <!-- Toplam fiyatı ekleyin -->
        
        <a href="{{ route('checkout.index') }}" class="btn btn-primary mt-4">Proceed to Checkout</a>
      </div>
    @else
      <p>Your cart is empty.</p>
    @endif
  </section>
@endsection
