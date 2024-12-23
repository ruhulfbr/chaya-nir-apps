<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    public function loginForm(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->back();
        }

        if (!str_contains(url()->previous(), 'login')) {
            session(['link' => url()->previous()]);
        }

        return view('auth.login-form');
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials['status'] = 'active';

        if (Auth::attempt($credentials, 100)) {
            $request->session()->regenerate();

            return redirect()->intended(session('link', '/'));
        }

        throw ValidationException::withMessages([
            'email' => __('auth.failed'),
        ])->redirectTo('/login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->back();
    }
}
