<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function __construct(private AuthService $authService)
    {
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $this->authService->register($validated);

        return redirect()->route('products.index')->with('success', 'Welcome! Your account has been created.');
    }

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! $this->authService->attemptLogin($credentials, $request->boolean('remember'))) {
            return back()->withErrors(['email' => 'Invalid credentials provided.'])->onlyInput('email');
        }

        $this->authService->finalizeLogin($request);

        return redirect()->intended(route('products.index'));
    }

    public function logout(Request $request)
    {
        $this->authService->logout($request);

        return redirect()->route('products.index')->with('success', 'Logged out successfully.');
    }
}
