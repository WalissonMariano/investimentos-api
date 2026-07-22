<?php

namespace App\Http\Controllers\Api;

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
            ->with('company')
            ->where('email', $request->validated('email'))
            ->first();

        if (! $user || ! Hash::check($request->validated('secret_token'), $user->secret_token)) {
            return response()->json([
                'message' => 'Credenciais inválidas.',
            ], 401);
        }

        $ttlMinutes = (int) config('jwt.ttl');
        $tokenTimeoutInSeconds = $ttlMinutes * 60;

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'auth_token' => $token,
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->name,
                'company_name' => $user->company->name,
                'company_id' => $user->company_id,
                'token_timeout_in_seconds' => $tokenTimeoutInSeconds,
            ],
        ]);
    }

    public function me(): JsonResponse
    {
        /** @var User $user */
        $user = auth('api')->user();
        $user->load('company');

        return response()->json([
            'id' => $user->id,
            'email' => $user->email,
            'name' => $user->name,
            'company_name' => $user->company->name,
            'company_id' => $user->company_id,
            'token_timeout_in_seconds' => (int) config('jwt.ttl') * 60,
        ]);
    }
}
