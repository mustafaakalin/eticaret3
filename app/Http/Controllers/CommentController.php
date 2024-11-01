<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, $productId)
    {
        // Kullanıcı oturumu kontrolü
        if (!auth()->check()) {
            return redirect()->route('login')->with('error', 'You need to be logged in to add a review.');
        }

        // Doğrulama işlemi
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:500',
        ]);

        // Güvenlik kontrolleri ve veri ekleme
        Comment::create([
            'user_id' => auth()->id(),
            'product_id' => $productId,
            'rating' => $validated['rating'],
            'comment' => strip_tags($validated['comment']), // XSS koruması için strip_tags kullanıyoruz.
        ]);

        return redirect()->back()->with('success', 'Your review has been submitted successfully.');
    }
}
