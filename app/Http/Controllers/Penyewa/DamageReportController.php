<?php

namespace App\Http\Controllers\Penyewa;

use App\Http\Controllers\Controller;
use App\Models\DamageReport;
use App\Models\UserRooms;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reports = DamageReport::with(['userRoom.room'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return Inertia::render('Penyewa/DamageReports/Index', [
            'reports' => $reports,
        ]);
    }

    public function create()
    {
        $user = Auth::user();
        // Get active room for the user to report damage on
        $activeRoom = UserRooms::where('user_id', $user->id)
            ->where('status', 'checked_in')
            ->with(['room'])
            ->first();

        if (!$activeRoom) {
            return redirect()->route('penyewa.dashboard')->with('error', 'Anda tidak memiliki kamar aktif untuk melaporkan kerusakan.');
        }

        return Inertia::render('Penyewa/DamageReports/Create', [
            'activeRoom' => $activeRoom,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = Auth::user();
        $activeRoom = UserRooms::where('user_id', $user->id)
            ->where('status', 'checked_in')
            ->first();

        if (!$activeRoom) {
            return back()->with('error', 'Anda tidak memiliki kamar aktif.');
        }

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('damage-reports', 'public');
        }

        DamageReport::create([
            'user_id' => $user->id,
            'user_room_id' => $activeRoom->id,
            'title' => $request->title,
            'description' => $request->description,
            'photo' => $photoPath,
            'status' => 'pending',
        ]);

        return redirect()->route('penyewa.damage-reports.index')->with('success', 'Laporan kerusakan berhasil dikirim.');
    }
}
