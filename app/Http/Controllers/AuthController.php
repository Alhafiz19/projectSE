<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Show Register Form
    public function showRegister() {
        return view('register');
    }

    // Handle Registration Logic
    public function register(Request $request) {
        // 1. Validate the data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed' // 'confirmed' expects a password_confirmation field
        ]);

        // 2. Create the user
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password) // Always hash passwords!
        ]);

        // 3. Login immediately or redirect to login
        return redirect()->route('login')->with('success', 'Account created! Please login.');
    }

    // Show Login Form
    public function showLogin() {
        return view('login');
    }

    // Handle Login Logic
    public function login(Request $request) {
        // 1. Validate
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        // 2. Attempt to login
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('/'); // Go to Home Page
        }

        // 3. If failed
        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    // Handle Logout
    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}