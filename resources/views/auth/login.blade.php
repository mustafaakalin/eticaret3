@extends('frontend.app')

@section('content')
<section class="p-10">
    <h1 class="text-4xl font-bold mb-6">Giriş Yap</h1>

    <form action="{{ route('login') }}" method="POST">
        @csrf

        <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700">E-posta</label>
            <input type="email" name="email" id="email" required class="input input-bordered w-full mt-1" placeholder="E-posta adresinizi girin">
            @error('email')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label for="password" class="block text-sm font-medium text-gray-700">Şifre</label>
            <input type="password" name="password" id="password" required class="input input-bordered w-full mt-1" placeholder="Şifrenizi girin">
            @error('password')
                <p class="text-red-600 text-sm">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary mt-4">Giriş Yap</button>
    </form>
</section>
@endsection
