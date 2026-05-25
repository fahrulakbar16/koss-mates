<?php

namespace App\Traits;

trait ApiResponse
{
    /**
     * Format standard API response.
     *
     * @param mixed $data
     * @param string|null $message
     * @param string $status
     * @param int $code
     * @return \Illuminate\Http\JsonResponse
     */
    public function apiResponse($data = null, $message = null, $status = 'success', $code = 200)
    {
        $response = [
            'status' => $status,
            'message' => $message,
        ];

        if (!is_null($data)) {
            $response['data'] = $data;
        }

        return response()->json($response, $code);
    }
}
