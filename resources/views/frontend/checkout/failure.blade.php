@extends('frontend.app')

@section('content')

@if (session('message'))
    <div class="alert alert-success" role="alert">
        {{ session('message') }}
    </div>
    
    
@endif


<section class="p-10">
    <h1 class="text-4xl font-bold mb-6">Payment Failed!</h1>
    <p class="mb-4">We’re sorry, but your payment could not be processed.</p>
    <p>Please try again or contact our support team for assistance.</p>

    <div class="mt-6">
        <a href="{{ route('checkout.index') }}" class="btn btn-primary">Try Again</a>
        <a href="{{ route('home') }}" class="btn btn-secondary">Return to Home</a>
    </div>
</section>
@endsection
