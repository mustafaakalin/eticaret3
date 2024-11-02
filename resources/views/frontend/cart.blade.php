@extends('frontend.app')

@section('content')

<!-- Cart Section -->
<section class="p-6 md:p-10">
    <h1 class="text-4xl font-bold mb-6">Shopping Cart</h1>
    
    @if ($cart && $cart->cartItems->isNotEmpty())
        <!-- Mobile View -->
        <div class="block md:hidden">
            <div class="space-y-4">
                @foreach ($cart->cartItems as $item)
                    <div class="flex flex-col bg-white shadow-lg rounded-lg p-4">
                        <div class="flex-shrink-0">
                            <img src="{{ $item->product->images->isNotEmpty() ? asset('storage/' . $item->product->images->first()->image_path) : asset('default_image.jpg') }}" 
                                 alt="{{ $item->product->name }}" 
                                 class="w-full h-32 object-cover rounded-lg">
                        </div>
                        <div class="mt-2">
                            <h2 class="font-bold text-lg">{{ $item->product->name }}</h2>
                            <div class="flex items-center justify-between mt-2">
                                <span class="text-gray-600">Price: ${{ number_format($item->product->price, 2) }}</span>
                                <span class="text-gray-600">Total: ${{ number_format($item->product->price * $item->quantity, 2) }}</span>
                            </div>
                            <div class="flex items-center justify-between mt-4">
                                <form action="{{ route('cart.update', $item->product_id) }}" method="POST" class="flex items-center">
                                    @csrf
                                    <button type="submit" name="action" value="decrease" class="btn btn-secondary btn-sm" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                    <input type="text" name="quantity" value="{{ $item->quantity }}" class="input input-bordered w-16 text-center mx-2" readonly>
                                    <button type="submit" name="action" value="increase" class="btn btn-secondary btn-sm">+</button>
                                </form>
                                <form action="{{ route('cart.remove', $item->product_id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="btn btn-error btn-sm">Remove</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <!-- Desktop View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="table w-full table-zebra shadow-lg rounded-lg">
                <!-- Head -->
                <thead>
                    <tr class="text-left">
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Total</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cart->cartItems as $item)
                        <tr class="text-center">
                            <td>
                                <div class="flex items-center justify-center space-x-3">
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
                                <div class="flex items-center justify-center">
                                    <form action="{{ route('cart.update', $item->product_id) }}" method="POST" class="flex items-center">
                                        @csrf
                                        <button type="submit" name="action" value="decrease" class="btn btn-secondary btn-sm" {{ $item->quantity <= 1 ? 'disabled' : '' }}>-</button>
                                        <input type="text" name="quantity" value="{{ $item->quantity }}" class="input input-bordered w-16 text-center mx-2" readonly>
                                        <button type="submit" name="action" value="increase" class="btn btn-secondary btn-sm">+</button>
                                    </form>
                                </div>
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
            
            <h3 class="text-lg">Total: ${{ number_format($totalPrice, 2) }}</h3>
            
            <a href="{{ route('checkout.index') }}" class="btn btn-primary mt-4">Proceed to Checkout</a>
        </div>
    @else
        <p>Your cart is empty.</p>
    @endif
</section>

@endsection
