<?php

namespace App\Http\Controllers\Admin;

use App\Actions\CheckIn\ApproveCheckInRequest;
use App\Actions\CheckIn\GetCheckInRequest;
use App\Actions\CheckIn\RejectCheckInRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveCheckInRequest as ApproveCheckInFormRequest;
use App\Http\Requests\RejectCheckInRequest as RejectCheckInFormRequest;
use Inertia\Inertia;

class CheckinRequestController extends Controller
{
    /**
     * Display a listing of pending check-in requests
     */
    public function index(\Illuminate\Http\Request $request)
    {
        // Get paginated user_rooms with pending check-in verification
        $requests = app(GetCheckInRequest::class)->execute($request);

        $requests->through(function ($req) {
            return [
                'id' => $req->id,
                'user_name' => $req->user->name,
                'user_phone' => $req->user->tenant?->phone ?? '-',
                'room_name' => $req->room->name,
                'boarding_house_name' => $req->room->boardingHouse->name,
                'foto_kamar' => $req->foto_kamar ? asset('storage/' . $req->foto_kamar) : null,
                'submitted_at' => $req->updated_at->format('d M Y H:i'),
                'start_date' => $req->created_at->format('d M Y H:i'),
                'end_date' => $req->tanggal_berakhir,
            ];
        });

        return Inertia::render('Admin/CheckinRequests/Index', [
            'requests' => $requests,
            'filters' => $request->only(['search']),
        ]);
    }

    /**
     * Approve a check-in request
     */
    public function approve(ApproveCheckInFormRequest $request, $id)
    {
        app(ApproveCheckInRequest::class)->execute($id);

        return redirect()->back()->with('success', 'Permintaan check-in berhasil disetujui');
    }

    /**
     * Reject a check-in request
     */
    public function reject(RejectCheckInFormRequest $request, $id)
    {
        app(RejectCheckInRequest::class)->execute($id, $request->input('reason'));

        return redirect()->back()->with('success', 'Permintaan check-in berhasil ditolak');
    }
}
