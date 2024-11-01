<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Fetch featured products (you might want to change the logic here)
        $featuredProducts = Product::where('featured', true)->take(4)->get(); // Adjust the number as needed
        return view('frontend.home', compact('featuredProducts'));
    }
}
