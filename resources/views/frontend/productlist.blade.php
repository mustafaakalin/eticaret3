@extends('frontend.app')

@section('content')

<!-- Product List Header -->
<header class="p-6 sm:p-10 bg-base-300 text-center">
    <h1 class="text-3xl sm:text-4xl md:text-5xl font-bold">Our Products</h1>
    <p class="py-2 sm:py-4">Browse through our wide selection of products.</p>
</header>

<!-- Category Filters and Search -->
<section class="p-4 sm:p-6 text-center">
    <h2 class="text-2xl sm:text-3xl font-bold mb-4">Categories</h2>
    <div class="flex flex-wrap justify-center gap-2 mb-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline mb-2 {{ is_null($selectedCategory) ? 'btn-active' : '' }}">All</a>
        @foreach($categories as $category)
            <a href="{{ route('products.index', ['category' => $category->id, 'search' => $searchTerm]) }}" class="btn btn-outline mb-2 {{ $selectedCategory == $category->id ? 'btn-active' : '' }}">{{ $category->name }}</a>
        @endforeach
    </div>

</section>

<!-- Product List -->
<section class="p-4 sm:p-10">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-6">
        @foreach ($products as $product)
            <a href="{{ route('product.show', $product->slug) }}">
                <div class="bg-base-100 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 p-4 flex flex-col items-center transform hover:-translate-y-1">
                    <figure class="w-full">
                        <img src="{{ $product->images->isNotEmpty() ? asset('storage/' . $product->images->first()->image_path) : asset('default_image.jpg') }}" 
                             alt="Product Image" class="rounded-lg w-full h-48 object-cover">
                    </figure>
                    <h2 class="text-lg sm:text-xl font-semibold mt-4 text-center">{{ $product->name }}</h2>
                    <p class="text-md sm:text-lg font-bold mt-1">${{ number_format($product->price, 2) }}</p>
                    <p class="text-sm text-gray-500 mt-1">Category: {{ $product->category->name }}</p>
                    <div class="mt-4">
                        <form action="{{ route('cart.add', $product->id) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-full mt-2">Add to Cart</button>
                        </form>
                    </div>
                </div>
            </a>
        @endforeach
    </div>

    <!-- Pagination Links -->
    <div class="mt-6">
        {{ $products->links() }}
    </div>
</section>



@endsection
