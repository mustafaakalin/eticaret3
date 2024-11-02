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
            <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
            <input type="email" name="email" id="email" required class="input input-bordered w-full mt-1" placeholder="E-posta adresinizi girin">
            @error('email')
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
