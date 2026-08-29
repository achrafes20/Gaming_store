<?php

namespace App\Http\Controllers;

use App\Services\UsersClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function login(Request $request, UsersClient $users)
    {
        $request->validate(['email' => 'required|email', 'password' => 'required|string']);

        $result = $users->login($request->only('email', 'password'));

        if ($result['status'] !== 200) {
            return back()->withErrors(['email' => $result['body']->message ?? 'Invalid credentials.']);
        }

        Session::put('jwt', $result['body']->token);
        Session::put('user', $result['body']->user);
        $this->cacheFavoriteIds($users);

        return redirect('/');
    }

    public function register(Request $request, UsersClient $users)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $result = $users->register($request->only('name', 'email', 'password', 'password_confirmation'));

        if ($result['status'] !== 201) {
            $message = $result['body']->errors ?? ['email' => [$result['body']->message ?? 'Registration failed.']];

            return back()->withErrors($message);
        }

        Session::put('jwt', $result['body']->token);
        Session::put('user', $result['body']->user);
        $this->cacheFavoriteIds($users);

        return redirect('/');
    }

    public function logout()
    {
        Session::forget(['jwt', 'user', 'favorite_ids']);

        return redirect('/');
    }

    private function cacheFavoriteIds(UsersClient $users): void
    {
        $favorites = $users->favorites()['body'];
        Session::put('favorite_ids', collect($favorites)->pluck('product_id')->all());
    }
}
