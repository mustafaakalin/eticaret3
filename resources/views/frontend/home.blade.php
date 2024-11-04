@extends('frontend.app')

@section('content')
<!-- Hero Section with Animated Text and Search -->
<div class="hero min-h-[80vh] bg-cover bg-center relative"
    style="background-image: url('{{ asset('images/hero-background.jpg') }}');">
  <div class="absolute inset-0 bg-black/50"></div>
  <div class="hero-content text-center text-white relative z-10">
    <div class="max-w-xl">
      <h1 class="text-5xl md:text-6xl font-bold mb-6 animate-fade-in">
        Discover Amazing Products
      </h1>
      <p class="text-xl md:text-2xl mb-8 animate-slide-up">
        Your One-Stop Shop for Quality and Style
      </p>

      <!-- Search Bar -->
      <div class="form-control w-full max-w-lg mx-auto mb-8">
        <div class="input-group">
          <input type="text" placeholder="Search products..." class="input input-bordered w-full" />
          <button class="btn btn-square btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
            </svg>
          </button>
        </div>
      </div>

      <!-- CTA Buttons -->
      <div class="flex flex-col sm:flex-row gap-4 justify-center">
        <button class="btn btn-primary btn-lg">Shop Now</button>
        <button class="btn btn-ghost btn-lg border-2 border-white hover:bg-white hover:text-black">
          View Categories
        </button>
      </div>
    </div>
  </div>
</div>

<!-- Stats Section -->
<div class="bg-base-200 py-8">
  <div class="container mx-auto grid grid-cols-2 md:grid-cols-4 gap-4 px-4">
    <div class="stat place-items-center">
      <div class="stat-title">Products</div>
      <div class="stat-value text-primary">12K+</div>
      <div class="stat-desc">From 100+ brands</div>
    </div>
    <div class="stat place-items-center">
      <div class="stat-title">Customers</div>
      <div class="stat-value text-secondary">50K+</div>
      <div class="stat-desc">Across the globe</div>
    </div>
    <div class="stat place-items-center">
      <div class="stat-title">Delivery</div>
      <div class="stat-value">24/7</div>
      <div class="stat-desc">Fast & reliable</div>
    </div>
    <div class="stat place-items-center">
      <div class="stat-title">Reviews</div>
      <div class="stat-value text-accent">4.8⭐</div>
      <div class="stat-desc">Customer rating</div>
    </div>
  </div>
</div>

<!-- Featured Products Section -->
<section class="py-12 px-4 bg-base-100">
  <div class="container mx-auto">
    <div class="flex justify-between items-center mb-8">
      <h2 class="text-3xl md:text-4xl font-bold">Featured Products</h2>
      <a href="{{ route('products.index') }}" class="btn btn-ghost">View All</a>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
      @foreach($featuredProducts as $product)
      <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 group">
        <figure class="relative overflow-hidden">
          <img src="{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('default_image.jpg') }}"
              alt="{{ $product->name }}"
              class="w-full h-56 object-cover group-hover:scale-110 transition-transform duration-300">
          @if($product->discount)
          <div class="absolute top-2 left-2 badge badge-secondary">-{{ $product->discount }}%</div>
          @endif
          @if($product->is_new)
          <div class="absolute top-2 right-2 badge badge-primary">New</div>
          @endif
        </figure>

        <div class="card-body">
          <h3 class="card-title text-lg group-hover:text-primary transition-colors">
            {{ $product->name }}
            @if($product->stock <= 5 && $product->stock > 0)
              <span class="badge badge-warning badge-sm">Low Stock</span>
              @endif
          </h3>

          <div class="flex items-center gap-2 my-2">
            <div class="rating rating-sm">
              @for($i = 1; $i <= 5; $i++) <input type="radio" name="rating-{{ $product->id }}"
                  class="mask mask-star-2 bg-orange-400" disabled {{ $i <=$product->rating ? 'checked' : '' }}/>
                @endfor
            </div>
            <span class="text-sm text-gray-600">({{ $product->reviews_count }})</span>
          </div>

          <div class="flex items-center gap-2">
            @if($product->old_price)
            <span class="text-sm line-through text-gray-500">${{ $product->old_price }}</span>
            @endif
            <span class="text-xl font-bold text-primary">${{ $product->price }}</span>
          </div>

          <div class="card-actions justify-between items-center mt-4">
            <button class="btn btn-circle btn-ghost btn-sm tooltip" data-tip="Add to Wishlist">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
            </button>

            <form action="{{ route('cart.add', $product->id) }}" method="POST" class="flex-1 ml-2">
              @csrf
              <button type="submit" class="btn btn-primary btn-block">Add to Cart</button>
            </form>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- Categories Section with Icons -->
<section class="py-12 px-4 bg-base-200">
  <div class="container mx-auto">
    <h2 class="text-3xl md:text-4xl font-bold text-center mb-8">Shop by Category</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-6 gap-4">
      @foreach($categories as $category)
      <a href="{{ url('category.show', $category->slug) }}"
          class="card bg-base-100 shadow-lg hover:shadow-xl transition-all duration-300 group">
        <div class="card-body items-center text-center p-4">
          <div
              class="w-16 h-16 rounded-full bg-primary/10 flex items-center justify-center group-hover:bg-primary/20 transition-colors">
            <i class="fas {{ $category->icon ?? 'fa-tags' }} text-2xl text-primary"></i>
          </div>
          <h3 class="font-semibold mt-3">{{ $category->name }}</h3>
          <p class="text-sm text-gray-600">{{ $category->products_count }} Products</p>
        </div>
      </a>
      @endforeach
    </div>
  </div>
</section>

<!-- Testimonials Section -->
<section class="py-12 px-4 bg-base-100">
  <div class="container mx-auto">
      <h2 class="text-3xl md:text-4xl font-bold text-center mb-12">What Our Customers Say</h2>
      
      <div class="carousel w-full max-w-4xl mx-auto">
          @foreach($testimonials as $index => $testimonial)
          <div id="slide{{ $index }}" class="carousel-item relative w-full">
              <div class="card bg-base-200 shadow-lg mx-8">
                  <div class="card-body text-center">
                      <div class="avatar mb-4">
                          <div class="w-24 rounded-full ring ring-primary ring-offset-base-100 ring-offset-2 mx-auto">
                              <img src="{{ $testimonial->avatar ?? asset('default-avatar.jpg') }}" alt="Customer Avatar" />
                          </div>
                      </div>
                      <div class="rating rating-sm mb-4">
                          @for($i = 1; $i <= 5; $i++)
                              <input type="radio" name="rating-{{ $index }}" 
                                     class="mask mask-star-2 bg-orange-400" disabled 
                                     {{ $i <= $testimonial->rating ? 'checked' : '' }}/>
                          @endfor
                      </div>
                      <p class="text-lg italic mb-4">"{{ $testimonial->content }}"</p>
                      <h3 class="font-bold text-xl">{{ $testimonial->author }}</h3>
                      <p class="text-gray-600">{{ $testimonial->position }}</p>
                  </div>
              </div>
              <div class="absolute flex justify-between transform -translate-y-1/2 left-0 right-0 top-1/2">
                  <a href="#slide{{ $index - 1 }}" class="btn btn-circle btn-primary ml-2">❮</a>
                  <a href="#slide{{ $index + 1 }}" class="btn btn-circle btn-primary mr-2">❯</a>
              </div>
          </div>
          @endforeach
      </div>
  </div>
</section>

<!-- Newsletter Section with Features -->
<section class="py-12 px-4 bg-base-200">
  <div class="container mx-auto max-w-6xl">
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div>
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Stay Updated</h2>
        <p class="text-lg mb-6">Subscribe to our newsletter and get exclusive offers, new product alerts, and insider
          deals!</p>

        <div class="flex flex-col gap-4">
          <div class="flex items-center gap-3">
            <div class="badge badge-primary p-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span>Early access to sales</span>
          </div>
          <div class="flex items-center gap-3">
            <div class="badge badge-primary p-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </div>
            <span>Exclusive discounts</span>
          </div>
          <div class="flex items-center gap-3">
            <div class="badge badge-primary p-3">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
              </svg>
            </div>
            <span>New arrival notifications</span>
          </div>
        </div>
      </div>

      <div class="card bg-base-100 shadow-xl">
        <div class="card-body">
          <form action="{{ url('newsletter.subscribe') }}" method="POST" class="space-y-4">
            @csrf
            <div class="form-control">
              <label class="label">
                <span class="label-text">Full Name</span>
              </label>
              <input type="text" name="name" placeholder="Enter your name" class="input input-bordered" required>
            </div>

            <div class="form-control">
              <label class="label">
                <span class="label-text">Email</span>
              </label>
              <input type="email" name="email" placeholder="Enter your email" class="input input-bordered" required>
            </div>

            <div class="form-control">
              <label class="label cursor-pointer">
                <span class="label-text">I agree to receive marketing emails</span>
                <input type="checkbox" class="checkbox checkbox-primary" required>
              </label>
            </div>

            <button type="submit" class="btn btn-primary w-full">Subscribe Now</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Features Section -->
<section class="py-12 px-4 bg-base-100">
  <div class="container mx-auto">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <div class="card bg-base-200">
        <div class="card-body items-center text-center">
          <div class="rounded-full bg-primary/10 p-4 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
            </svg>
          </div>
          <h3 class="card-title">Free Shipping</h3>
          <p class="text-sm">On orders over $100</p>
        </div>
      </div>

      <div class="card bg-base-200">
        <div class="card-body items-center text-center">
          <div class="rounded-full bg-primary/10 p-4 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
            </svg>
          </div>
          <h3 class="card-title">Secure Payment</h3>
          <p class="text-sm">100% secure payment</p>
        </div>
      </div>

      <div class="card bg-base-200">
        <div class="card-body items-center text-center">
          <div class="rounded-full bg-primary/10 p-4 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
            </svg>
          </div>
          <h3 class="card-title">Easy Returns</h3>
          <p class="text-sm">30 day return policy</p>
        </div>
      </div>

      <div class="card bg-base-200">
        <div class="card-body items-center text-center">
          <div class="rounded-full bg-primary/10 p-4 mb-4">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-primary" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" />
            </svg>
          </div>
          <h3 class="card-title">24/7 Support</h3>
          <p class="text-sm">Dedicated support</p>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Brands Carousel -->
<section class="py-12 px-4 bg-base-200">
  <div class="container mx-auto">
    <h2 class="text-3xl md:text-4xl font-bold text-center mb-8">Our Trusted Brands</h2>
    <div class="flex flex-wrap justify-center gap-8 items-center">
      @foreach($brands as $brand)
      <div class="w-32 h-32 flex items-center justify-center">
        <img src="{{ asset('storage/' . $brand->logo) }}" alt="{{ $brand->name }}"
            class="max-w-full max-h-full object-contain grayscale hover:grayscale-0 transition-all duration-300">
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- Mobile App Download -->
<section class="py-12 px-4 bg-base-100">
  <div class="container mx-auto">
    <div class="grid md:grid-cols-2 gap-8 items-center">
      <div class="order-2 md:order-1">
        <img src="{{ asset('images/mobile-app.png') }}" alt="Mobile App" class="max-w-sm mx-auto">
      </div>
      <div class="order-1 md:order-2">
        <h2 class="text-3xl md:text-4xl font-bold mb-4">Download Our App</h2>
        <p class="text-lg mb-6">Shop on the go with our mobile app. Get exclusive app-only offers and push notifications
          for your orders.</p>
        <div class="flex flex-wrap gap-4">
          <a href="#" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
              <path
                  d="M17.112 0H6.888C3.085 0 0 3.085 0 6.888v10.224C0 20.915 3.085 24 6.888 24h10.224c3.803 0 6.888-3.085 6.888-6.888V6.888C24 3.085 20.915 0 17.112 0zM12 18.75c-3.728 0-6.75-3.022-6.75-6.75S8.272 5.25 12 5.25s6.75 3.022 6.75 6.75-3.022 6.75-6.75 6.75z" />
            </svg>
            App Store
          </a>
          <a href="#" class="btn btn-primary gap-2">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
              <path
                  d="M3.375 0h17.25C22.941 0 24 1.059 24 3.375v17.25C24 22.941 22.941 24 20.625 24H3.375C1.059 24 0 22.941 0 20.625V3.375C0 1.059 1.059 0 3.375 0zM12 18.75c3.728 0 6.75-3.022 6.75-6.75S15.728 5.25 12 5.25 5.25 8.272 5.25 12s3.022 6.75 6.75 6.75z" />
            </svg>
            Google Play
          </a>
        </div>
      </div>
    </div>
  </div>
</section>

<!-- Instagram Feed -->
{{-- <section class="py-12 px-4 bg-base-200">
  <div class="container mx-auto">
    <h2 class="text-3xl md:text-4xl font-bold text-center mb-8">Follow Us on Instagram</h2>
    <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
      @foreach($instagramPosts as $post)
      <a href="{{ $post->link }}" target="_blank" class="relative group overflow-hidden">
        <img src="{{ $post->image }}" alt="Instagram Post"
            class="w-full aspect-square object-cover group-hover:scale-110 transition-transform duration-300">
        <div
            class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center text-white">
          <div class="text-center">
            <div class="flex items-center gap-2">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                  stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
              </svg>
              {{ $post->likes }}
            </div>
          </div>
        </div>
      </a>
      @endforeach
    </div>
    <div class="text-center mt-8">
      <a href="#" class="btn btn-primary">Follow Us @YourStore</a>
    </div>
  </div>
</section> --}}

@endsection

@push('scripts')
<script>
  // Smooth scroll for anchor links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector(this.getAttribute('href')).scrollIntoView({
                behavior: 'smooth'
            });
        });
    });

    // Add to cart animation
    document.querySelectorAll('form[action^="{{ route('cart.add', '') }}"]').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const btn = this.querySelector('button[type="submit"]');
            btn.classList.add('loading');
            
            setTimeout(() => {
                btn.classList.remove('loading');
                btn.textContent = 'Added!';
                btn.classList.add('btn-success');
                
                setTimeout(() => {
                    btn.textContent = 'Add to Cart';
                    btn.classList.remove('btn-success');
                    this.submit();
                }, 1000);
            }, 800);
        });
    });
</script>
@endpush