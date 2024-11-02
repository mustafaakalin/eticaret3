@extends('frontend.app')

@section('content')
<section class="p-4 md:p-10 max-w-md mx-auto bg-white rounded-lg shadow-md">
    <h1 class="text-3xl md:text-4xl font-bold mb-6 text-center">Şifre Sıfırlama</h1>

    @if (session('status'))
        <div class="alert alert-success shadow-lg mb-6">
            <div>
                <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current flex-shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                <span>{{ session('status') }}</span>
            </div>
        </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" class="space-y-4">
        @csrf

        <div class="form-control w-full">
            <label for="email" class="label">
                <span class="label-text font-semibold text-gray-700">E-posta Adresi</span>
            </label>
            <input type="email" name="email" id="email" required class="input input-bordered w-full" placeholder="E-posta adresinizi girin">
            @error('email')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary w-full mt-4">Şifre Sıfırlama Bağlantısını Gönder</button>
    </form>
</section>
@endsection
