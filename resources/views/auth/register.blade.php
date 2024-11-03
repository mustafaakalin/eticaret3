@extends('frontend.app')

@section('content')
<section class="p-10 max-w-md mx-auto">
    <h1 class="text-4xl font-bold mb-6 text-center">Kayıt Ol</h1>

    @if (session('warning'))
        <div class="alert alert-warning mb-4">
            {{ session('warning') }}
        </div>
    @endif

    <form action="{{ route('register') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="name" class="block text-sm font-medium text-gray-700">Adınız</label>
            <input type="text" name="name" id="name" required class="input input-bordered w-full mt-1" placeholder="Adınızı girin">
            @error('name')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="surname" class="block text-sm font-medium text-gray-700">Soyadınız</label>
            <input type="text" name="surname" id="surname" required class="input input-bordered w-full mt-1" placeholder="Soyadınızı girin">
            @error('surname')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
            <input type="email" name="email" id="email" required class="input input-bordered w-full mt-1" placeholder="E-posta adresinizi girin">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="identity_number" class="block text-sm font-medium text-gray-700">Kimlik Numarası</label>
            <input type="text" name="identity_number" id="identity_number" class="input input-bordered w-full mt-1" placeholder="Kimlik numaranızı girin">
            @error('identity_number')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="address" class="block text-sm font-medium text-gray-700">Adres</label>
            <input type="text" name="address" id="address" class="input input-bordered w-full mt-1" placeholder="Adresinizi girin">
            @error('address')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="city" class="block text-sm font-medium text-gray-700">Şehir</label>
            <input type="text" name="city" id="city" class="input input-bordered w-full mt-1" placeholder="Şehrinizi girin">
            @error('city')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="country" class="block text-sm font-medium text-gray-700">Ülke</label>
            <input type="text" name="country" id="country" class="input input-bordered w-full mt-1" placeholder="Ülkenizi girin">
            @error('country')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="zip_code" class="block text-sm font-medium text-gray-700">Posta Kodu</label>
            <input type="text" name="zip_code" id="zip_code" class="input input-bordered w-full mt-1" placeholder="Posta kodunuzu girin">
            @error('zip_code')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700">Şifre</label>
            <input type="password" name="password" id="password" required class="input input-bordered w-full mt-1" placeholder="Şifrenizi girin">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Şifre Tekrar</label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="input input-bordered w-full mt-1" placeholder="Şifrenizi tekrar girin">
        </div>

        <button type="submit" class="btn btn-primary mt-4 w-full">Kayıt Ol</button>
    </form>
</section>
@endsection
