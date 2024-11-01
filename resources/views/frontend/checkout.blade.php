@extends('frontend.app')

@section('content')
<section class="p-10">
    <h1 class="text-4xl font-bold mb-6">Checkout</h1>

    <form action="{{ route('checkout.process') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">Adınız</label>
            <input type="text" name="name" id="name" required class="input input-bordered w-full mt-1" placeholder="Adınızı girin">
            @error('name')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="surname" class="block text-sm font-medium text-gray-700">Soyadınız</label>
            <input type="text" name="surname" id="surname" required class="input input-bordered w-full mt-1" placeholder="Soyadınızı girin">
            @error('surname')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
            <input type="email" name="email" id="email" required class="input input-bordered w-full mt-1" placeholder="E-posta adresinizi girin">
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="address" class="block text-sm font-medium text-gray-700">Adres</label>
            <textarea name="address" id="address" required class="input input-bordered w-full mt-1" placeholder="Adresinizi girin"></textarea>
            @error('address')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="city" class="block text-sm font-medium text-gray-700">Şehir</label>
            <input type="text" name="city" id="city" required class="input input-bordered w-full mt-1" placeholder="Şehir girin">
            @error('city')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="zip_code" class="block text-sm font-medium text-gray-700">Posta Kodu</label>
            <input type="text" name="zip_code" id="zip_code" required class="input input-bordered w-full mt-1" placeholder="Posta kodunu girin">
            @error('zip_code')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <h2 class="text-2xl font-bold mb-4">Ödeme Bilgileri</h2>

        <div class="mb-6">
            <label for="card_number" class="block text-sm font-medium text-gray-700">Kart Numarası</label>
            <input type="text" name="card_number" id="card_number" required class="input input-bordered w-full mt-1" placeholder="Kart numaranızı girin">
            @error('card_number')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6 flex space-x-4">
            <div class="flex-1">
                <label for="expiry_date" class="block text-sm font-medium text-gray-700">Son Kullanma Tarihi</label>
                <input type="text" name="expiry_date" id="expiry_date" required class="input input-bordered w-full mt-1" placeholder="AA/YYYY">
                @error('expiry_date')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex-1">
                <label for="cvc" class="block text-sm font-medium text-gray-700">CVC</label>
                <input type="text" name="cvc" id="cvc" required class="input input-bordered w-full mt-1" placeholder="CVC kodunu girin">
                @error('cvc')
                    <p class="text-red-600 text-sm">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <input type="hidden" name="total_price" value="{{ $totalPrice }}">

        <button type="submit" class="btn btn-primary mt-4">Ödeme Yap</button>
    </form>
</section>
@endsection
