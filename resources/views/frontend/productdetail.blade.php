@extends('frontend.app')

@section('content')

<!-- Product Details Section -->
<section class="p-8 md:p-12 lg:p-16 bg-gradient-to-b from-base-200 to-base-100">
  <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
    <!-- Product Image Carousel -->
    <figure class="w-full h-full bg-white rounded-lg shadow-lg overflow-hidden">
      @if($product->images->count() > 1)
      <!-- DaisyUI Carousel for Multiple Images -->
      <div class="carousel w-full h-full">
        @foreach($product->images as $index => $image)
        <div id="slide{{ $index + 1 }}" class="carousel-item relative w-full">
          <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image"
              class="w-full h-full object-cover">

          <!-- Carousel Navigation Buttons -->
          <div class="absolute left-5 right-5 top-1/2 flex -translate-y-1/2 transform justify-between">
            <a href="#slide{{ $index == 0 ? $product->images->count() : $index }}" class="btn btn-circle">❮</a>
            <a href="#slide{{ $index + 2 > $product->images->count() ? 1 : $index + 2 }}" class="btn btn-circle">❯</a>
          </div>
        </div>
        @endforeach
      </div>

      @else
      <!-- Single Image Display -->
      <img src="{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('default_image.jpg') }}"
          alt="Product Image" class="w-full h-full object-cover transition-transform duration-300 hover:scale-105">
      @endif
    </figure>

    <!-- Product Information -->
    <div class="flex flex-col justify-between">
      <div>
        <h1 class="text-5xl font-extrabold text-primary mb-4">{{ $product->name }}</h1>
        <p class="text-3xl text-gray-700 font-semibold mb-6">${{ number_format($product->price, 2) }}</p>
        <p class="text-lg text-gray-600 leading-relaxed mb-8">{{ $product->description }}</p>

        <!-- Product Options -->
        <div class="flex items-center space-x-4 mb-6">
          <label for="quantity" class="font-medium text-lg">Quantity:</label>
          <input type="number" id="quantity" name="quantity" min="1" value="1" class="input input-bordered w-20">
        </div>

        <!-- Add to Cart & Buy Now Buttons -->
        <div class="flex space-x-4">
          <form action="{{ route('cart.add', $product->id) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary w-full">Add to Cart</button>
          </form>
        </div>
      </div>

      <!-- Tags Section -->
      <div class="mt-8">
        <h3 class="text-xl font-bold text-gray-700 mb-2">Tags:</h3>
        <div class="flex flex-wrap gap-2">
          @foreach($product->tags as $tag)
          <span class="badge badge-outline badge-primary text-sm">{{ $tag->name }}</span>
          @endforeach
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Customer Reviews Section -->
<section class="p-8 md:p-12 lg:p-16 bg-base-200">
  <h2 class="text-4xl font-bold text-center mb-10">Customer Reviews</h2>

  @forelse($comments as $comment)
  <div class="p-6 bg-white rounded-lg shadow-lg mb-6 max-w-3xl mx-auto">
    <div class="flex items-center justify-between">
      <h3 class="text-2xl font-bold text-gray-800">{{ $comment->user->name }}</h3>
      <p class="text-sm text-gray-500">{{ $comment->created_at->format('F j, Y') }}</p>
    </div>
    <p class="text-gray-700 mt-4">{{ $comment->comment }}</p>
    <!-- DaisyUI Rating Component for Customer Rating -->
    <div class="rating mt-2">
      @for ($i = 1; $i <= $comment->rating; $i++)
      <input type="radio" name="rating-{{ $comment->id }}" class="mask mask-star-2 bg-yellow-400" disabled value="{{ $i }}" {{ $i == $comment->rating ? 'checked=checked' : '' }} />
    @endfor
    </div>
  </div>
  @empty
  <p class="text-center text-gray-500">There are no reviews for this product yet.</p>
  @endforelse
</section>

<!-- Add Comment Section -->
<section class="p-8 md:p-12 lg:p-16 bg-base-100">
  <h2 class="text-4xl font-bold text-center mb-10">Add a Review</h2>
  <form action="{{ route('comments.store', $product->id) }}" method="POST" class="max-w-2xl mx-auto space-y-6">
    @csrf
    <div>
      <label for="rating" class="block font-medium text-lg text-gray-800">Rating</label>
      <select name="rating" id="rating" class="select select-bordered w-full" required>
        <option value="5">★★★★★ - Excellent</option>
        <option value="4">★★★★☆ - Good</option>
        <option value="3">★★★☆☆ - Average</option>
        <option value="2">★★☆☆☆ - Poor</option>
        <option value="1">★☆☆☆☆ - Very Poor</option>
      </select>
      @error('rating')
      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <div>
      <label for="comment" class="block font-medium text-lg text-gray-800">Comment</label>
      <textarea name="comment" id="comment" rows="4" class="textarea textarea-bordered w-full" required></textarea>
      @error('comment')
      <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
      @enderror
    </div>

    <button type="submit" class="btn btn-primary w-full">Submit Review</button>
  </form>
</section>

@endsection