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
    /**
     * @OA\Post(
     *     path="/api/auth/login",
     *     operationId="authLogin",
     *     tags={"Auth"},
     *     summary="Login da API",
     *     description="Autentica com e-mail e secret_token e retorna um JWT.",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","secret_token"},
     *             @OA\Property(property="email", type="string", format="email", example="admin@admin.com"),
     *             @OA\Property(property="secret_token", type="string", example="admin")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(property="auth_token", type="string", example="eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9..."),
     *             @OA\Property(
     *                 property="user",
     *                 type="object",
     *                 @OA\Property(property="id", type="string", format="uuid"),
     *                 @OA\Property(property="email", type="string", example="admin@admin.com"),
     *                 @OA\Property(property="name", type="string", example="admin"),
     *                 @OA\Property(property="token_timeout_in_seconds", type="integer", example=86400)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Credenciais inválidas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Credenciais inválidas.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Erro de validação",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="The email field is required."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     )
     * )
     */
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

    /**
     * @OA\Get(
     *     path="/api/auth",
     *     operationId="authMe",
     *     tags={"Auth"},
     *     summary="Usuário autenticado",
     *     description="Retorna os dados do usuário a partir do JWT.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Usuário autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="id", type="string", format="uuid"),
     *             @OA\Property(property="email", type="string", example="admin@admin.com"),
     *             @OA\Property(property="name", type="string", example="admin"),
     *             @OA\Property(property="token_timeout_in_seconds", type="integer", example=86400)
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     )
     * )
     */
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
