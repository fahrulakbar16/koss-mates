<?php

namespace App\Http\Controllers;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LoginGoogle;
use App\Actions\Auth\LogoutAction;
use App\Actions\Auth\RegisterAction;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Helpers\LogActivityHelper;
use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return Inertia::render('Admin/Auth/Login');
    }

    public function auth(LoginRequest $request)
    {
        try {
            app(LoginAction::class)->execute($request);

            return redirect()->intended('/dashboard')->with('success', 'Login berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        app(LogoutAction::class)->execute($request);

        return redirect()->route('login');
    }

    public function register()
    {
        return Inertia::render('Admin/Auth/Register');
    }

    public function store(RegisterRequest $request)
    {
        try {
            $user = app(RegisterAction::class)->execute($request);

            // Automatically login the user after registration
            Auth::login($user);
            return redirect()->route('dashboard')->with('success', 'Registrasi berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function loginWithGoogle(Request $request)
    {
        try {
            $user = app(LoginGoogle::class)->execute($request);

            Auth::login($user);

            return redirect()->intended('/dashboard')->with('success', 'Login berhasil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
