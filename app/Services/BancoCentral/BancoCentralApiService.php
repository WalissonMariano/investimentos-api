<?php

namespace App\Services\BancoCentral;

use Illuminate\Support\Facades\Http;

class BancoCentralApiService
{

    private const SELIC_SERIE = '11';
    private const IPCA_SERIE = '10843';

    public function getSelicPeriodo(string $dataInicio, string $dataFim): array
    {
        $response = Http::get(config('services.banco_central.base_url') . 'bcdata.sgs.' . self::SELIC_SERIE . '/dados?formato=json&dataInicial='.$dataInicio.'&dataFinal='.$dataFim);
        return $response->json();

    }

    public function getIpcaPeriodo(string $dataInicio, string $dataFim): array
    {
        $response = Http::get(config('services.banco_central.base_url') . 'bcdata.sgs.' . self::IPCA_SERIE . '/dados?formato=json&dataInicial='.$dataInicio.'&dataFinal='.$dataFim);
        return $response->json();
 
    }
}