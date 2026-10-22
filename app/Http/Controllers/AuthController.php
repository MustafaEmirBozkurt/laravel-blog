<?php

namespace App\Http\Controllers;

use App\Services\UserStore;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct(private UserStore $users)
    {
    }

    public function showLogin(Request $request)
    {
        if ($request->session()->get('blog_auth')) {
            return redirect()->route('posts.index');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $username = (string) $request->input('username');
        $password = (string) $request->input('password');

        $adminUser = (string) config('blog.username');
        $adminPass = (string) config('blog.password');


        if ($adminPass !== ''
            && hash_equals($adminUser, $username)
            && hash_equals($adminPass, $password)) {
            return $this->loginAs($request, $adminUser, 'admin');
        }

        $user = $this->users->findByUsername($username);
        if ($user && Hash::check($password, $user['password'])) {
            return $this->loginAs($request, $user['username'], 'user');
        }

        return back()->withInput($request->only('username'))
            ->with('error', 'Kullanıcı adı veya şifre hatalı.');
    }

    public function showRegister(Request $request)
    {
        if ($request->session()->get('blog_auth')) {
            return redirect()->route('posts.index');
        }
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $data = $request->validate([
            'username' => [
                'required', 'string', 'min:3', 'max:30', 'regex:/^[A-Za-z0-9_]+$/',
                function ($attribute, $value, $fail) {
                    $taken = strcasecmp($value, (string) config('blog.username')) === 0
                        || $this->users->findByUsername($value) !== null;
                    if ($taken) {
                        $fail('Bu kullanıcı adı zaten alınmış.');
                    }
                },
            ],
            'password' => 'required|string|min:8|confirmed',
        ], [
            'username.required' => 'Kullanıcı adı gerekli.',
            'username.min'      => 'Kullanıcı adı en az 3 karakter olmalı.',
            'username.max'      => 'Kullanıcı adı en fazla 30 karakter olabilir.',
            'username.regex'    => 'Kullanıcı adı sadece harf, rakam ve alt çizgi içerebilir.',
            'password.required' => 'Şifre gerekli.',
            'password.min'      => 'Şifre en az 8 karakter olmalı.',
            'password.confirmed' => 'Şifre tekrarı eşleşmiyor.',
        ]);

        $this->users->create($data['username'], $data['password']);

        return $this->loginAs($request, $data['username'], 'user', 'Kayıt başarılı, hoş geldiniz!');
    }

    public function logout(Request $request)
    {
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('posts.index')->with('success', 'Çıkış yapıldı.');
    }

    private function loginAs(Request $request, string $username, string $role, string $message = 'Giriş yapıldı.')
    {
        $request->session()->regenerate();
        $request->session()->put([
            'blog_auth' => true,
            'blog_user' => $username,
            'blog_role' => $role,
        ]);

        return redirect()->route('posts.index')->with('success', $message);
    }
}
