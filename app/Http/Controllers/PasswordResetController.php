<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use App\Models\User;

class PasswordResetController extends Controller
{
    // Şifre sıfırlama talep formunu göster
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    // Şifre sıfırlama linkini gönder
    public function sendResetLinkEmail(Request $request)
    {
        // Rate limiting kullanıcının IP adresi üzerinden
        $key = 'reset-password:' . $request->ip();
        if (RateLimiter::tooManyAttempts($key, 5)) {
            return back()->with('warning', __('Çok fazla istek gönderildi. Lütfen birkaç dakika sonra tekrar deneyin.'));
        }
        
        RateLimiter::hit($key, 60); // Bir dakika için sınırlandırma

        // E-posta adresi doğrulama
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', __('Lütfen geçerli bir e-posta adresi girin.'));
        }

        // Şifre sıfırlama linki gönderimi
        $response = Password::sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? back()->with('success', __('Şifre sıfırlama bağlantısı e-posta adresinize gönderildi.'))
            : back()->with('error', __('E-posta adresi bulunamadı.'));
    }

    // Yeni şifre formunu göster
    public function showResetForm(Request $request, $token = null)
    {
        if (!$token || !Password::tokenExists(User::where('email', $request->email)->first(), $token)) {
            return redirect()->route('password.request')->with('warning', __('Geçersiz veya süresi dolmuş token.'));
        }
        
        return view('auth.passwords.reset')->with(['token' => $token, 'email' => $request->email]);
    }

    // Şifreyi sıfırla
    public function reset(Request $request)
    {
        // Şifre doğrulaması
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|confirmed|min:8|different:old_password',
            'token' => 'required',
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput()->with('error', __('Lütfen tüm alanları doğru şekilde doldurun.'));
        }

        // Kullanıcıyı bul
        $user = User::where('email', $request->email)->first();

        // Şifreyi sıfırla
        $response = Password::reset($request->only('email', 'password', 'password_confirmation', 'token'), function ($user, $password) {
            if (password_verify($password, $user->password)) {
                return back()->with('warning', __('Yeni şifre eski şifrenizle aynı olamaz.'));
            }

            $user->password = bcrypt($password);
            $user->save();
        });

        return $response == Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __('Şifreniz başarıyla sıfırlandı. Giriş yapabilirsiniz.'))
            : back()->with('error', __('Şifre sıfırlama işlemi başarısız oldu.'));
    }
}
