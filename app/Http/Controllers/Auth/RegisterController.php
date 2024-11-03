<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        // Form verilerini doğrulama
        $this->validator($request->all())->validate();

        // Kullanıcıyı oluşturma
        $user = $this->create($request->all());

        // İsterseniz kullanıcıyı giriş yapabilirsiniz
        auth()->login($user);

        // Kayıt olduktan sonra yönlendirme
        return redirect()->route('home')->with('success', 'Kayıt başarılı! Hoş geldiniz.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'], // New field
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'identity_number' => ['nullable', 'string', 'max:11'], // New field
            'address' => ['nullable', 'string', 'max:255'], // New field
            'city' => ['nullable', 'string', 'max:255'], // New field
            'country' => ['nullable', 'string', 'max:255'], // New field
            'zip_code' => ['nullable', 'string', 'max:10'], // New field
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            // Add any other necessary fields with validation
        ]);
    }

    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'surname' => $data['surname'], // New field
            'email' => $data['email'],
            'identity_number' => $data['identity_number'], // New field
            'address' => $data['address'], // New field
            'city' => $data['city'], // New field
            'country' => $data['country'], // New field
            'zip_code' => $data['zip_code'], // New field
            'password' => Hash::make($data['password']),
        ]);

        // Varsayılan rol ataması
        $user->assignRole('user'); // veya gerekli rol ismi

        return $user;
    }
}
