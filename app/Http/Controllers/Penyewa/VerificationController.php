<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Notifications\PenyewaVerifyEmail;
use Illuminate\Auth\Events\Verified;

class VerificationController extends Controller
{
    /**
     * Resend the email verification notification.
     */
    public function resend(Request $request)
    {
        if ($request->user()->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        $request->user()->notify(new PenyewaVerifyEmail);

        return back()->with('success', 'Tautan verifikasi baru telah dikirim ke alamat email Anda.');
    }

    /**
     * Mark the authenticated user's email address as verified.
     */
    public function verify(Request $request, $id, $hash)
    {
        $user = \App\Models\User::findOrFail($id);

        if (! hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            abort(403);
        }

        if ($user->hasVerifiedEmail()) {
            return \Inertia\Inertia::render('Penyewa/Auth/VerifyEmailSuccess', [
                'message' => 'Email sudah diverifikasi sebelumnya.'
            ]);
        }

        if ($user->markEmailAsVerified()) {
            event(new \Illuminate\Auth\Events\Verified($user));
        }

        return \Inertia\Inertia::render('Penyewa/Auth/VerifyEmailSuccess', [
            'message' => 'Email Anda berhasil diverifikasi. Anda sekarang dapat mengakses semua layanan kami.'
        ]);
    }
}
