<?php

namespace App\Actions\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutAction
{
    /**
     * Logout user.
     */
    public function execute(Request $request): void
    {
        $user = Auth::user();
        $userName = $user ? $user->name : 'Unknown';

        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
    }
}
