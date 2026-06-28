<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePenyewaEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user && $user->primary_role === 'Penyewa') {
            $emailVerified = !is_null($user->email_verified_at);

            // Check if tenant relation exists and phone is verified
            $tenant = $user->tenant;
            $phoneVerified = $tenant && !is_null($tenant->phone_verified_at);

            if (!$emailVerified) {
                if ($request->expectsJson() || $request->is('api/*')) {
                    return response()->json([
                        'message' => 'Detail kontak Anda belum terverifikasi seluruhnya.',
                        'data' => [
                            'email_verified' => $emailVerified,
                            'phone_verified' => 1
                        ]
                    ], 403);
                }

                // If not JSON/API, maybe redirect?
                // We'll pass the verification statuses as query parameters to the notice page
                return redirect()->route('penyewa.verification.notice', [
                    'email_verified' => $emailVerified ? 1 : 0,
                    'phone_verified' => 1
                ]);
            }
        }

        return $next($request);
    }
}
