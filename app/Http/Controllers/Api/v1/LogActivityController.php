<?php

namespace App\Http\Controllers\Api\v1;

use App\Http\Controllers\Controller;
use App\Models\LogActivity;
use App\Http\Resources\LogActivityResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogActivityController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
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

        if ($user->hasRole('Penyewa')) {
            $query->where('subject', 'NOT LIKE', '%login%')
                ->where('subject', 'NOT LIKE', '%logout%')
                ->where('subject', 'NOT LIKE', '%registrasi%');
        }

        $logs = $query->paginate($request->input('limit', 20));

        return LogActivityResource::collection($logs);
    }
}
