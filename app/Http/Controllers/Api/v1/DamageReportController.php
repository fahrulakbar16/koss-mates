<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\DamageReport\CreateDamageReport;
use App\Http\Controllers\Controller;
use App\Http\Requests\DamageReport\StoreDamageReportRequest;
use App\Models\DamageReport;
use App\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class DamageReportController extends Controller
{
    use ApiResponse;

    public function index()
    {
        $user = Auth::user();
        $reports = DamageReport::with(['userRoom.room'])
            ->where('user_id', $user->id)
            ->latest()
            ->paginate(10);

        return $this->apiResponse('Laporan kerusakan berhasil diambil', $reports);
    }

    /**
     * Store a newly created damage report in storage.
     */
    public function store(StoreDamageReportRequest $request): JsonResponse
    {
        app(CreateDamageReport::class)->execute(
            Auth::user(),
            $request->validated(),
            $request->file('photo')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Laporan kerusakan berhasil dikirim'
        ], 201);
    }
}
