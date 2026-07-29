<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiRequestLogController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermometroRequest;
use App\Services\Termometro\TermometroService;
use Illuminate\Http\JsonResponse;

class TermometroController extends Controller
{
    public function index(
        TermometroRequest $request,
        TermometroService $termometroService
    ): JsonResponse {
        $valor = (float) $request->validated('valor');
        $dataInicio = $request->validated('dataInicio');
        $dataFim = $request->validated('dataFim');
        $userId = auth('api')->id();

        try {
            $resultado = $termometroService->calcular($valor, $dataInicio, $dataFim);

            ApiRequestLogController::record(
                action: 'termometro',
                method: $request->method(),
                endpoint: '/api/termometro',
                statusCode: 200,
                userId: $userId,
                ip: $request->ip(),
                meta: [
                    'valor' => $valor,
                    'dataInicio' => $dataInicio,
                    'dataFim' => $dataFim,
                ],
            );

            return response()->json($resultado, 200);
        } catch (\Exception $e) {
            ApiRequestLogController::record(
                action: 'termometro',
                method: $request->method(),
                endpoint: '/api/termometro',
                statusCode: 500,
                userId: $userId,
                ip: $request->ip(),
                meta: [
                    'valor' => $valor,
                    'dataInicio' => $dataInicio,
                    'dataFim' => $dataFim,
                    'error' => $e->getMessage(),
                ],
            );

            return response()->json([
                'message' => 'Erro ao calcular termometro',
            ], 500);
        }
    }
}
