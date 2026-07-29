<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\ApiRequestLogController;
use App\Http\Controllers\Controller;
use App\Http\Requests\TermometroRequest;
use App\Services\Termometro\TermometroService;
use Illuminate\Http\JsonResponse;

class TermometroController extends Controller
{
    /**
     * @OA\Get(
     *     path="/api/termometro",
     *     operationId="termometroIndex",
     *     tags={"Termômetro"},
     *     summary="Calcular termômetro do poder de compra",
     *     description="Compara o capital em cenários de caixa, CDI/Selic e dólar, descontando o IPCA no período informado.",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="valor",
     *         in="query",
     *         required=true,
     *         description="Capital inicial em BRL",
     *         @OA\Schema(type="number", format="float", example=10000, minimum=0.01)
     *     ),
     *     @OA\Parameter(
     *         name="dataInicio",
     *         in="query",
     *         required=true,
     *         description="Data inicial do período (Y-m-d)",
     *         @OA\Schema(type="string", format="date", example="2024-01-01")
     *     ),
     *     @OA\Parameter(
     *         name="dataFim",
     *         in="query",
     *         required=true,
     *         description="Data final do período (Y-m-d)",
     *         @OA\Schema(type="string", format="date", example="2024-12-31")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Cálculo realizado com sucesso",
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="periodo",
     *                 type="object",
     *                 @OA\Property(property="inicio", type="string", example="2024-01-01"),
     *                 @OA\Property(property="fim", type="string", example="2024-12-31")
     *             ),
     *             @OA\Property(property="capital_inicial_brl", type="number", example=10000),
     *             @OA\Property(
     *                 property="fatores",
     *                 type="object",
     *                 @OA\Property(property="ipca", type="number", example=1.05),
     *                 @OA\Property(property="cdi", type="number", example=1.10),
     *                 @OA\Property(property="usd", type="number", example=1.12)
     *             ),
     *             @OA\Property(
     *                 property="cenarios",
     *                 type="object",
     *                 @OA\Property(
     *                     property="caixa",
     *                     type="object",
     *                     @OA\Property(property="final_nominal", type="number", example=10000),
     *                     @OA\Property(property="poder_compra", type="number", example=9523.81),
     *                     @OA\Property(property="ganho_real", type="number", example=-476.19)
     *                 ),
     *                 @OA\Property(
     *                     property="cdi",
     *                     type="object",
     *                     @OA\Property(property="final_nominal", type="number", example=11000),
     *                     @OA\Property(property="poder_compra", type="number", example=10476.19),
     *                     @OA\Property(property="ganho_real", type="number", example=476.19)
     *                 ),
     *                 @OA\Property(
     *                     property="dolar",
     *                     type="object",
     *                     @OA\Property(property="final_nominal", type="number", example=11200),
     *                     @OA\Property(property="poder_compra", type="number", example=10666.67),
     *                     @OA\Property(property="ganho_real", type="number", example=666.67)
     *                 )
     *             ),
     *             @OA\Property(
     *                 property="veredito",
     *                 type="object",
     *                 @OA\Property(property="melhor_cenario", type="string", example="dolar"),
     *                 @OA\Property(property="cdi_bateu_ipca", type="boolean", example=true),
     *                 @OA\Property(property="dolar_bateu_ipca", type="boolean", example=true),
     *                 @OA\Property(property="dolar_bateu_cdi", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Não autenticado",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=422,
     *         description="Parâmetros inválidos",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="O valor é obrigatório."),
     *             @OA\Property(property="errors", type="object")
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Erro ao calcular ou consultar fontes externas",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Erro ao calcular termometro")
     *         )
     *     )
     * )
     */
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
