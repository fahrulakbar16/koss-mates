<?php

namespace App\Http\Controllers\Api\v1\Pengelola;

use App\Actions\CheckIn\ApproveCheckInRequest;
use App\Actions\CheckIn\GetCheckInRequest;
use App\Actions\CheckIn\RejectCheckInRequest;
use App\Http\Controllers\Controller;
use App\Http\Requests\ApproveCheckInRequest as ApproveCheckInFormRequest;
use App\Http\Requests\RejectCheckInRequest as RejectCheckInFormRequest;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class CheckinRequestController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of pending check-in requests
     */
    public function index(Request $request)
    {
        // Get all user_rooms with pending check-in verification
        $data = app(GetCheckInRequest::class)->execute($request);

        return $this->apiResponse($data, 'Data Berhasil Diambil');
    }

    /**
     * Approve a check-in request
     */
    public function approve(ApproveCheckInFormRequest $request, $id)
    {
        app(ApproveCheckInRequest::class)->execute($id);

        return $this->apiResponse(null, 'Permintaan check-in berhasil disetujui');
    }

    /**
     * Reject a check-in request
     */
    public function reject(RejectCheckInFormRequest $request, $id)
    {
        app(RejectCheckInRequest::class)->execute($id, $request->input('reason'));

        return $this->apiResponse(null, 'Permintaan check-in berhasil ditolak');
    }
}
