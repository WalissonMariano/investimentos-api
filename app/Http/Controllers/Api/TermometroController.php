<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\TermometroRequest;
use App\Services\Termometro\TermometroService;

class TermometroController extends Controller
{

    public function index(
        TermometroRequest $request, 
        TermometroService $termometroService
        ): JsonResponse
    {
        
        try {

            $resultado = $termometroService->calcular(
                (float) $request->validated('valor'),
                $request->validated('dataInicio'),
                $request->validated('dataFim')
            );

            return response()->json($resultado, 200);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao calcular termometro',
            ], 500);
        }
    }   

}
