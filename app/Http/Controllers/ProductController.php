<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Algolia\AlgoliaSearch\SearchClient;

class ProductController extends Controller
{
    // Algolia client'ını başlat
    protected $algolia;

    public function __construct()
    {
        $this->algolia = SearchClient::create(config('scout.algolia.id'), config('scout.algolia.secret'));
    }


    public function index(Request $request)
    {
        $request->validate([
            'category' => 'nullable|integer|exists:categories,id',
            'search' => 'nullable|string|max:255', // Arama terimi için doğrulama
        ]);
        
        $categories = Category::all();
        $selectedCategory = $request->get('category');
        $searchTerm = $request->get('search');

        // Algolia'da arama yap
        if ($searchTerm) {
            $products = $this->algolia->initIndex('products')->search($searchTerm);
            $products = collect($products['hits']); // Algolia'dan dönen sonuçları koleksiyon haline getir
        } else {
            $products = Product::when($selectedCategory, function ($query) use ($selectedCategory) {
                return $query->where('category_id', $selectedCategory);
            })->paginate(10); // Use paginate instead of get
        }

        return view('frontend.productlist', compact('products', 'categories', 'selectedCategory', 'searchTerm'));
    }


    public function show($slug)
    {
        // Find the product by slug with its related category and tags
        $product = Product::with('category', 'tags')->where('slug', $slug)->firstOrFail();
        $comments = $product->comments()->with('user')->latest()->get();
        
        return view('frontend.productdetail', compact(['product', 'comments']));
    }

}
