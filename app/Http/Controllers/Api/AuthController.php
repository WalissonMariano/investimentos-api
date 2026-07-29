<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiRequestLogController;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::query()
            ->where('email', $request->validated('email'))
            ->first();

        if (! $user || ! Hash::check($request->validated('secret_token'), $user->secret_token)) {
            ApiRequestLogController::record(
                action: 'auth.failed',
                method: $request->method(),
                endpoint: '/api/auth/login',
                statusCode: 401,
                ip: $request->ip(),
                meta: ['email' => $request->validated('email')],
            );

            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        $ttlMinutes = (int) config('jwt.ttl');
        $tokenTimeoutInSeconds = $ttlMinutes * 60;

        $token = JWTAuth::fromUser($user);

        ApiRequestLogController::record(
            action: 'auth.login',
            method: $request->method(),
            endpoint: '/api/auth/login',
            statusCode: 200,
            userId: $user->id,
            ip: $request->ip(),
            meta: ['email' => $user->email],
        );

        return response()->json([
            'auth_token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'token_timeout_in_seconds' => $tokenTimeoutInSeconds,
            ],
        ]);
    }

    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();

        ApiRequestLogController::record(
            action: 'auth.me',
            method: request()->method(),
            endpoint: '/api/auth',
            statusCode: 200,
            userId: $user->id,
            ip: request()->ip(),
        );

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'token_timeout_in_seconds' => (int) config('jwt.ttl') * 60,
        ]);
    }
}
