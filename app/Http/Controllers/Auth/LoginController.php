<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Filament\Facades\Filament;

class LoginController extends Controller
{


    public function showLoginForm()
    {
        if (auth()->check()) {
            return redirect('/admin');
        }
        return view('login');
    }

   public function login(Request $request)
{
    $credentials = $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
    ]);

    $remember = $request->boolean('remember'); // Explicitly get remember value

    if (Auth::attempt($credentials, $remember)) {
        $request->session()->regenerate();
        
        // Log the remember token for debugging
        \Log::info('Remember token created: ' . ($remember ? 'yes' : 'no'));
        
        return redirect('/admin');
    }

    return back()->withErrors([
        'email' => 'The provided credentials do not match our records.',
    ]);
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}