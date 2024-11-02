@extends('frontend.app')

@section('content')
<section class="p-6 md:p-10 max-w-md mx-auto bg-white rounded-lg shadow-lg">
    <h1 class="text-3xl md:text-4xl font-bold text-center mb-8">Şifreyi Sıfırla</h1>

    <form action="{{ route('password.update') }}" method="POST" class="space-y-6">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">
        <input type="hidden" name="email" value="{{ $email }}">

        <div class="form-control w-full">
            <label for="password" class="label">
                <span class="label-text font-semibold text-gray-700">Yeni Şifre</span>
            </label>
            <input type="password" name="password" id="password" required class="input input-bordered w-full" placeholder="Yeni şifrenizi girin">
            @error('password')
                <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-control w-full">
            <label for="password_confirmation" class="label">
                <span class="label-text font-semibold text-gray-700">Şifre Tekrar</span>
            </label>
            <input type="password" name="password_confirmation" id="password_confirmation" required class="input input-bordered w-full" placeholder="Yeni şifrenizi tekrar girin">
        </div>

        <button type="submit" class="btn btn-primary w-full mt-4">Şifreyi Sıfırla</button>
    </form>
</section>
@endsection
