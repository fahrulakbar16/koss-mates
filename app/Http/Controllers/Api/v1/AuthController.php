<?php

namespace App\Http\Controllers\Api\v1;

use App\Actions\Auth\LoginAction;
use App\Actions\Auth\LoginGoogle;
use App\Actions\Auth\RegisterAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\UserResource;
use Illuminate\Http\Request;

class AuthController extends Controller
{

    /**
     * Login a user.
     */
    public function login(LoginRequest $request)
    {
        $user = app(LoginAction::class)->execute($request);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Anda berhasil login',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
            ]
        ], 200);
    }

    /**
     * Login a user with Google.
     */
    public function loginGoogle(Request $request)
    {
        $user = app(LoginGoogle::class)->execute($request);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Anda berhasil login',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user),
            ]
        ], 200);
    }

    /**
     * Register a new user.
     */
    public function register(RegisterRequest $request)
    {
        $user = app(RegisterAction::class)->execute($request);

        $user->update([
            'active_at' => now(),
        ]);

        $token = $user->createToken('api-token')->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Registrasi berhasil',
            'data' => [
                'token' => $token,
                'user' => new UserResource($user->load('tenant')),
            ]
        ], 201);
    }

    /**
     * Logout a user.
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Anda berhasil logout',
        ], 200);
    }
}
