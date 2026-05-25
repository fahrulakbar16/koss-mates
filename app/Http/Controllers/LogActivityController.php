<?php

namespace App\Http\Controllers;

use App\Models\LogActivity;
use App\Http\Resources\LogActivityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LogActivityController extends Controller
{
    public function index(Request $request)
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Superadmin and Pengelola can see all logs
        // Others (Pemilik, Penyewa) can only see their own logs
        $query = LogActivity::with('user')->latest();

        if (!$user->hasAnyRole(['Superadmin', 'Pengelola'])) {
            $query->where('user_id', $user->id);
        }

        $logs = $query->paginate(20);

        return Inertia::render('Admin/ActivityLogs/Index', [
            'logs' => LogActivityResource::collection($logs),
        ]);
    }
}
