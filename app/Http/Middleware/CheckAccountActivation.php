<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckAccountActivation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Only apply to authenticated users who have 'Penyewa' role (or don't have superadmin/pengelola)
        // If we want this to apply strictly to users who haven't activated:
        if ($user && $user->active_at === null) {
            // Check if they already submitted activation request
            $aktivasi = $user->aktivasiAkun;

            if (!$aktivasi) {
                // If they haven't submitted, redirect to form
                if (!$request->is('aktivasi-akun/form') && !$request->is('aktivasi-akun/store')) {
                    return redirect()->route('aktivasi-akun.form');
                }
            } else {
                // If they have submitted, redirect to review page
                if (!$request->is('aktivasi-akun/review')) {
                    return redirect()->route('aktivasi-akun.review');
                }
            }
        }

        return $next($request);
    }
}
