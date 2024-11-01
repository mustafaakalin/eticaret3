@extends('frontend.app')

@section('content')
<section class="p-10 bg-base-200">
    <div class="max-w-2xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h1 class="text-4xl font-bold text-center text-green-600 mb-6">Payment Successful!</h1>
        <p class="text-lg mb-4">Thank you for your purchase!</p>
        <p class="mb-4">Your order has been successfully processed.</p>
        <p class="mb-4">If you have any questions or need assistance, feel free to contact our support team.</p>

        @if (session('message'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                <strong class="font-bold">Success!</strong>
                <span class="block sm:inline">{{ session('success') }}</span>
            </div>
        @endif

        <div class="mt-6">
            <a href="{{ route('home') }}" class="btn btn-primary w-full">Return to Home</a>
        </div>
    </div>
</section>
@endsection
