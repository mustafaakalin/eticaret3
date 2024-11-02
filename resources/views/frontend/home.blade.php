@extends('frontend.app')

@section('content')
    
  <!-- Hero Section -->
  <div class="hero min-h-screen bg-base-300">
    <div class="hero-content text-center">
      <div class="max-w-md mx-auto">
        <h1 class="text-4xl md:text-5xl font-bold">Welcome to Our Shop!</h1>
        <p class="py-4 md:py-6">Find the best products here at amazing prices.</p>
        <button class="btn btn-primary">Shop Now</button>
      </div>
    </div>
  </div>

  <!-- Featured Products Section -->
  <section class="p-4 md:p-10">
    <h2 class="text-3xl md:text-4xl font-bold mb-6 text-center">Featured Products</h2>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 md:gap-6">
      @foreach($featuredProducts as $product)
      <a href="{{ route('product.show' , $product->slug) }}">
        <div class="card bg-base-100 shadow-xl">
          
          <figure class="overflow-hidden">
            <img 
              src="{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('default_image.jpg') }}" 
              alt="Product Image" 
              class="w-full h-48 object-cover"
            >
          </figure>

          <div class="card-body">
            <h2 class="card-title text-lg md:text-xl">{{ $product->name }}</h2>
            <p class="text-md md:text-lg font-semibold">${{ $product->price }}</p>
            <div class="card-actions justify-end">
              
              <!-- Add to Cart Form -->
              <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex items-center space-x-2">
                @csrf
                <input 
                  type="number" 
                  name="quantity" 
                  value="1" 
                  min="1" 
                  class="input input-bordered w-16 md:w-20"
                >
                <button type="submit" class="btn btn-primary">Add to Cart</button>
              </form>
              
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
  </section>

@endsection
