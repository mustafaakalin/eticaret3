<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GoogleAuthController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            // Kullanıcı mevcut değilse, yeni bir kullanıcı oluştur
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'password' => bcrypt(uniqid()), // Varsayılan bir şifre oluştur
                'avatar' => $googleUser->getAvatar(), // Avatarı kaydet
            ]);
        }

        // Kullanıcıyı oturum aç
        Auth::login($user, true);

        // İstediğiniz sayfaya yönlendirin
        return redirect()->intended('/'); // Anasayfaya yönlendirme
    }
}
