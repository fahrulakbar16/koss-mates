<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\AktivasiAkun;

class AktivasiAkunController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        if ($user->active_at || $user->aktivasiAkun) {
            return redirect()->route('dashboard'); // Or penyewa.rooms.index
        }

        return Inertia::render('Penyewa/AktivasiAkun/Form');
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        
        if ($user->active_at || $user->aktivasiAkun) {
            return redirect()->route('dashboard');
        }

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'id_card_number' => 'required|string|max:50',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'payment_package' => 'required|string',
            'entry_date' => 'required|date',
        ]);

        $validated['user_id'] = $user->id;
        $validated['status'] = 'pending';

        AktivasiAkun::create($validated);

        return redirect()->route('aktivasi-akun.review');
    }

    public function review()
    {
        $user = Auth::user();
        
        if ($user->active_at) {
            return redirect()->route('dashboard');
        }

        if (!$user->aktivasiAkun) {
            return redirect()->route('aktivasi-akun.form');
        }

        return Inertia::render('Penyewa/AktivasiAkun/Review');
    }
}
