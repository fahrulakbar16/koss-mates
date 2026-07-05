<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\AktivasiAkun;
use App\Models\Tenant;

class AktivasiAkunController extends Controller
{
    public function index()
    {
        $aktivasi = AktivasiAkun::with('user')->orderBy('created_at', 'desc')->get();
        return Inertia::render('Admin/AktivasiAkun/Index', [
            'aktivasi' => $aktivasi
        ]);
    }

    public function edit($id)
    {
        $aktivasi = AktivasiAkun::with('user')->findOrFail($id);
        return Inertia::render('Admin/AktivasiAkun/Edit', [
            'aktivasi' => $aktivasi
        ]);
    }

    public function update(Request $request, $id)
    {
        $aktivasi = AktivasiAkun::findOrFail($id);

        $validated = $request->validate([
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'id_card_number' => 'required|string|max:50',
            'gender' => 'required|in:Laki-laki,Perempuan',
            'birth_date' => 'required|date',
            'payment_package' => 'required|string',
            'entry_date' => 'required|date',
        ]);

        $aktivasi->update($validated);

        return redirect()->route('admin.aktivasi-akun.index')->with('success', 'Data aktivasi berhasil diperbarui.');
    }

    public function approve($id)
    {
        $aktivasi = AktivasiAkun::with('user')->findOrFail($id);
        $aktivasi->update(['status' => 'approved']);

        $user = $aktivasi->user;
        $user->update(['active_at' => now()]);

        // Sync or Create Tenant profile
        Tenant::updateOrCreate(
            ['user_id' => $user->id],
            [
                'phone' => $aktivasi->phone,
                'address' => $aktivasi->address,
                'id_card_number' => $aktivasi->id_card_number,
                'birth_date' => $aktivasi->birth_date,
            ]
        );

        return redirect()->route('admin.aktivasi-akun.index')->with('success', 'Akun berhasil diaktifkan.');
    }

    public function reject($id)
    {
        $aktivasi = AktivasiAkun::findOrFail($id);
        $aktivasi->update(['status' => 'rejected']);

        // Optionally delete it so they can resubmit, or keep it rejected so they can see status and we can let them re-submit by deleting on create.
        // Usually deleting is easier for resubmission. Let's delete it.
        $aktivasi->delete();

        return redirect()->route('admin.aktivasi-akun.index')->with('success', 'Aktivasi akun ditolak.');
    }
}
