@extends('frontend.app')

@section('content')
<div class="min-h-screen bg-gradient-to-b from-base-100 to-base-200">
    <!-- Breadcrumb -->
    <div class="max-w-7xl mx-auto px-4 py-4">
        <div class="text-sm breadcrumbs">
            <ul>
                <li><a href="{{ route('home') }}">Home</a></li>
                <li><a href="{{ url('categories.show', $product->category->slug) }}">{{ $product->category->name }}</a></li>
                <li>{{ $product->name }}</li>
            </ul>
        </div>
    </div>

    <!-- Product Main Section -->
    <div class="max-w-7xl mx-auto px-4 py-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
            <!-- Image Gallery -->
            <div class="space-y-4">
                <div class="card bg-base-100 shadow-xl">
                    @if($product->images->count() > 1)
                        <div class="carousel w-full aspect-square">
                            @foreach($product->images as $index => $image)
                                <div id="slide{{ $index + 1 }}" class="carousel-item relative w-full">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         class="w-full object-cover" 
                                         alt="{{ $product->name }}" />
                                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                                        <a href="#slide{{ $index == 0 ? $product->images->count() : $index }}" 
                                           class="btn btn-circle btn-ghost hover:bg-base-200/80">❮</a>
                                        <a href="#slide{{ $index + 2 > $product->images->count() ? 1 : $index + 2 }}" 
                                           class="btn btn-circle btn-ghost hover:bg-base-200/80">❯</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <!-- Thumbnail Navigation -->
                        <div class="flex gap-2 p-4 overflow-x-auto">
                            @foreach($product->images as $index => $image)
                                <a href="#slide{{ $index + 1 }}" 
                                   class="w-20 h-20 rounded-lg overflow-hidden hover:ring-2 hover:ring-primary transition">
                                    <img src="{{ asset('storage/' . $image->image_path) }}" 
                                         class="w-full h-full object-cover" 
                                         alt="Thumbnail {{ $index + 1 }}" />
                                </a>
                            @endforeach
                        </div>
                    @else
                        <figure class="aspect-square">
                            <img src="{{ $product->images->isNotEmpty() 
                                ? asset('storage/' . $product->images->first()->image_path) 
                                : asset('default_image.jpg') }}" 
                                 class="w-full h-full object-cover" 
                                 alt="{{ $product->name }}" />
                        </figure>
                    @endif
                </div>
            </div>

            <!-- Product Info -->
            <div class="space-y-6">
                <div class="space-y-4">
                    <!-- Tags & Badges -->
                    <div class="flex flex-wrap gap-2">
                        @if($product->is_new)
                            <div class="badge badge-primary">New</div>
                        @endif
                        @if($product->discount > 0)
                            <div class="badge badge-secondary">{{ $product->discount }}% OFF</div>
                        @endif
                        @if($product->is_featured)
                            <div class="badge badge-accent">Featured</div>
                        @endif
                    </div>

                    <!-- Product Title & Brand -->
                    <h1 class="text-4xl font-bold text-base-content">{{ $product->name }}</h1>
                    <div class="flex items-center gap-2">
                        <span class="text-sm text-base-content/70">by</span>
                        
                           @if($product->brand && $product->brand->slug)
                              <a href="{{ url('brands.show', $product->brand->slug) }}">{{ $product->brand->name }}</a>

                              
                          @else
                              <!-- If slug is null, display nothing or an alternative message -->
                              <span>No Brand</span> <!-- or leave it empty -->
                          @endif

                    </div>

                    <!-- Rating Summary -->
                    <div class="flex items-center gap-4">
                        <div class="rating rating-sm">
                            @for($i = 1; $i <= 5; $i++)
                                <input type="radio" 
                                       class="mask mask-star-2 bg-warning" 
                                       disabled 
                                       {{ $i <= $product->rating ? 'checked' : '' }} />
                            @endfor
                        </div>
                        <span class="text-sm text-base-content/70">
                            {{ $product->rating }} ({{ $product->reviews_count }} reviews)
                        </span>
                    </div>

                    <!-- Price Section -->
                    <div class="flex items-baseline gap-4">
                        <span class="text-3xl font-bold text-primary">
                            ${{ number_format($product->price, 2) }}
                        </span>
                        @if($product->old_price)
                            <span class="text-xl line-through text-base-content/50">
                                ${{ number_format($product->old_price, 2) }}
                            </span>
                        @endif
                    </div>

                    <!-- Stock Status -->
                    <div class="flex items-center gap-2">
                        <div class="badge {{ $product->stock > 0 ? 'badge-success' : 'badge-error' }}">
                            {{ $product->stock > 0 ? 'In Stock' : 'Out of Stock' }}
                        </div>
                        @if($product->stock > 0)
                            <span class="text-sm text-base-content/70">
                                {{ $product->stock }} units available
                            </span>
                        @endif
                    </div>

                    <!-- Description -->
                    <p class="text-base-content/80 leading-relaxed">
                        {{ $product->description }}
                    </p>

                    <!-- Specifications -->
                    @if($product->specifications)
                        <div class="collapse collapse-plus bg-base-200">
                            <input type="checkbox" /> 
                            <div class="collapse-title text-xl font-medium">
                                Specifications
                            </div>
                            <div class="collapse-content">
                                <table class="table table-zebra w-full">
                                    <tbody>
                                        @foreach($product->specifications as $key => $value)
                                            <tr>
                                                <td class="font-medium">{{ $key }}</td>
                                                <td>{{ $value }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Add to Cart Form -->
                    @if($product->stock > 0)
                        <form action="{{ route('cart.add', $product->id) }}" 
                              method="POST" 
                              class="space-y-4">
                            @csrf
                            <div class="flex items-center gap-4">
                                <div class="form-control w-24">
                                    <label class="label">
                                        <span class="label-text">Quantity</span>
                                    </label>
                                    <input type="number" 
                                           name="quantity" 
                                           min="1" 
                                           max="{{ $product->stock }}" 
                                           value="1" 
                                           class="input input-bordered w-full" />
                                </div>
                            </div>
                            <div class="flex gap-4">
                                <button type="submit" class="btn btn-primary flex-1">
                                    Add to Cart
                                </button>
                                <button type="button" 
                                        class="btn btn-outline btn-primary" 
                                        onclick="window.location='{{ route('checkout.index', ['product_id' => $product->id]) }}'">
                                    Buy Now
                                </button>
                            </div>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Section -->
    <div class="max-w-7xl mx-auto px-4 py-12">
        <div class="card bg-base-100 shadow-xl">
            <div class="card-body">
                <h2 class="card-title text-2xl mb-6">Customer Reviews</h2>

                <!-- Reviews List -->
                <div class="space-y-6">
                    @forelse($product->comments as $comment)
                        <div class="border-b border-base-300 pb-6 last:border-b-0">
                            <div class="flex justify-between items-start">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <div class="avatar placeholder">
                                            <div class="bg-neutral text-neutral-content rounded-full w-8">
                                                <span>{{ substr($comment->user->name, 0, 1) }}</span>
                                            </div>
                                        </div>
                                        <span class="font-medium">{{ $comment->user->name }}</span>
                                    </div>
                                    <div class="rating rating-sm mt-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <input type="radio" 
                                                   class="mask mask-star-2 bg-warning" 
                                                   disabled 
                                                   {{ $i <= $comment->rating ? 'checked' : '' }} />
                                        @endfor
                                    </div>
                                </div>
                                <span class="text-sm text-base-content/60">
                                    {{ $comment->created_at->diffForHumans() }}
                                </span>
                            </div>
                            <p class="mt-3 text-base-content/80">{{ $comment->comment }}</p>
                        </div>
                    @empty
                        <div class="text-center py-8 text-base-content/60">
                            No reviews yet. Be the first to review this product!
                        </div>
                    @endforelse
                </div>

                <!-- Add Review Form -->
                @auth
                    <form action="{{ route('comments.store', $product->id) }}" 
                          method="POST" 
                          class="mt-8 space-y-4">
                        @csrf
                        <h3 class="text-xl font-medium">Write a Review</h3>
                        
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Rating</span>
                            </label>
                            <div class="rating rating-lg">
                                @for($i = 1; $i <= 5; $i++)
                                    <input type="radio" 
                                           name="rating" 
                                           value="{{ $i }}" 
                                           class="mask mask-star-2 bg-warning" 
                                           {{ old('rating') == $i ? 'checked' : '' }} />
                                @endfor
                            </div>
                            @error('rating')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text">Your Review</span>
                            </label>
                            <textarea name="comment" 
                                      class="textarea textarea-bordered h-24" 
                                      placeholder="Share your thoughts about this product...">{{ old('comment') }}</textarea>
                            @error('comment')
                                <label class="label">
                                    <span class="label-text-alt text-error">{{ $message }}</span>
                                </label>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">
                            Submit Review
                        </button>
                    </form>
                @else
                    <div class="alert alert-info mt-8">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" class="stroke-current shrink-0 w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            Please <a href="{{ route('filament.admin.auth.login') }}" class="link">login</a> to write a review.
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection