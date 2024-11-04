<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class GithubAuthController extends Controller
{
    public function redirectToGithub()
    {
        return Socialite::driver('github')->redirect();
    }

    public function handleGithubCallback()
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::where('email', $githubUser->getEmail())->first();

        if (!$user) {
            // Kullanıcı mevcut değilse, yeni bir kullanıcı oluştur
            $user = User::create([
                'name' => $githubUser->getName() ?: $githubUser->getNickname(),
                'email' => $githubUser->getEmail(),
                'password' => bcrypt(uniqid()), // Varsayılan bir şifre oluştur
                'avatar' => $githubUser->getAvatar(), // Avatarı kaydet
            ]);
        }


        // Varsayılan rol ataması
        $user->assignRole('user');

        // Kullanıcıyı oturum aç
        Auth::login($user, true);

        // İstediğiniz sayfaya yönlendirin
        return redirect()->intended('/'); // Anasayfaya yönlendirme
    }
}
