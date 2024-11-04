<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use App\Models\Testimonial;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('frontend.home', [
            'featuredProducts' => Product::with(['images', 'category'])
                ->where('is_featured', true)
                ->where('is_active', true)
                ->take(8)
                ->get(),
                
            'categories' => Category::where('is_active', true)
                ->orderBy('sort_order')
                ->withCount('products')
                ->get(),
                
            'testimonials' => Testimonial::where('is_active', true)
                ->take(5)
                ->get(),
                
            'brands' => Brand::where('is_active', true)
                ->get(),
                
            // 'instagramPosts' => InstagramPost::where('is_active', true)
            //     ->latest()
            //     ->take(6)
            //     ->get(),
        ]);
    }
}
