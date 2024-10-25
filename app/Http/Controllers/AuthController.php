<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Show the login page
    public function showLoginForm()
    {
        return view('login');
    }

    // Perform login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'], // Validate the username
            'password' => ['required'],
        ]);

        // Attempt login with username instead of email
        if (Auth::attempt(['username' => $credentials['username'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();
            return redirect()->intended('/movies');
        }

        return back()->with('error', 'The provided credentials do not match our records.');
    }

    // Perform logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
