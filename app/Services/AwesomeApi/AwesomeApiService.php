<?php
namespace App\Services\AwesomeApi;

use Illuminate\Support\Facades\Http;

class AwesomeApiService
{
    public function getCotacaoPeriodo(string $dataInicio, string $dataFim): array
    {
        $response = Http::get(config('services.awesomeapi.base_url') . 'daily/USD-BRL/?start_date='.$dataInicio.'&end_date='.$dataFim);
        return $response->json();
    }
}