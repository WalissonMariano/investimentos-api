<?php

namespace App\Http\Controllers;

use App\Models\ApiRequestLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ApiRequestLogController extends Controller
{
    public function index(): JsonResponse
    {
        $logs = ApiRequestLog::query()
            ->with('user:id,name,email')
            ->orderByDesc('created_at')
            ->limit(100)
            ->get();

        return response()->json($logs);
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'user_id' => ['nullable', 'uuid', 'exists:users,id'],
            'action' => ['required', 'string', 'max:50'],
            'method' => ['required', 'string', 'max:10'],
            'endpoint' => ['required', 'string', 'max:255'],
            'status_code' => ['required', 'integer', 'min:100', 'max:599'],
            'ip' => ['nullable', 'ip'],
            'meta' => ['nullable', 'array'],
        ]);

        $log = self::record(
            action: $data['action'],
            method: $data['method'],
            endpoint: $data['endpoint'],
            statusCode: $data['status_code'],
            userId: $data['user_id'] ?? Auth::id(),
            ip: $data['ip'] ?? $request->ip(),
            meta: $data['meta'] ?? null,
        );

        return response()->json($log, 201);
    }

    public static function record(
        string $action,
        string $method,
        string $endpoint,
        int $statusCode,
        ?string $userId = null,
        ?string $ip = null,
        ?array $meta = null,
    ): ApiRequestLog {
        return ApiRequestLog::create([
            'user_id' => $userId,
            'action' => $action,
            'method' => strtoupper($method),
            'endpoint' => $endpoint,
            'status_code' => $statusCode,
            'ip' => $ip,
            'meta' => $meta,
        ]);
    }
}
