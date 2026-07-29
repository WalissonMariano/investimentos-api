<?php

namespace App\OpenApi;

/**
 * @OA\Info(
 *     title="Investimentos API",
 *     version="1.0.0",
 *     description="API do Termômetro do Poder de Compra. Compara capital em caixa, CDI/Selic e dólar descontando o IPCA."
 * )
 *
 * @OA\Server(
 *     url="/",
 *     description="Servidor atual"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Informe o JWT retornado em POST /api/auth/login"
 * )
 *
 * @OA\Tag(
 *     name="Auth",
 *     description="Autenticação JWT da API"
 * )
 *
 * @OA\Tag(
 *     name="Termômetro",
 *     description="Cálculo do poder de compra (caixa × CDI × dólar × IPCA)"
 * )
 */
class OpenApiSpec
{
}
