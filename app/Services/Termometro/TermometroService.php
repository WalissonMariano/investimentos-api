<?php

namespace App\Services\Termometro;

use App\Services\AwesomeApi\AwesomeApiService;
use App\Services\BancoCentral\BancoCentralApiService;
use RuntimeException;

class TermometroService
{
    public function __construct(
        private readonly AwesomeApiService $awesomeApiService,
        private readonly BancoCentralApiService $bancoCentralApiService,
    ) {}

    /**
     * @return array{
     *     periodo: array{inicio: string, fim: string},
     *     capital_inicial_brl: float,
     *     fatores: array{ipca: float, cdi: float, usd: float},
     *     cenarios: array{
     *         caixa: array{final_nominal: float, poder_compra: float, ganho_real: float},
     *         cdi: array{final_nominal: float, poder_compra: float, ganho_real: float},
     *         dolar: array{final_nominal: float, poder_compra: float, ganho_real: float}
     *     },
     *     veredito: array{
     *         melhor_cenario: string,
     *         cdi_bateu_ipca: bool,
     *         dolar_bateu_ipca: bool,
     *         dolar_bateu_cdi: bool
     *     }
     * }
     */
    public function calcular(float $valor, string $dataInicio, string $dataFim): array
    {
        $dataInicioYmd = date('Ymd', strtotime($dataInicio));
        $dataFimYmd = date('Ymd', strtotime($dataFim));
        $dataInicioBcb = date('d/m/Y', strtotime($dataInicio));
        $dataFimBcb = date('d/m/Y', strtotime($dataFim));

        $cotacoes = $this->awesomeApiService->getCotacaoPeriodo($dataInicioYmd, $dataFimYmd);
        $selic = $this->bancoCentralApiService->getSelicPeriodo($dataInicioBcb, $dataFimBcb);
        $ipca = $this->bancoCentralApiService->getIpcaPeriodo($dataInicioBcb, $dataFimBcb);

        $fatorIpca = $this->acumularFatorPercentual($ipca);
        $fatorCdi = $this->acumularFatorPercentual($selic);
        $fatorUsd = $this->calcularFatorUsd($cotacoes);

        $caixa = $this->montarCenario($valor, 1.0, $fatorIpca);
        $cdi = $this->montarCenario($valor, $fatorCdi, $fatorIpca);
        $dolar = $this->montarCenario($valor, $fatorUsd, $fatorIpca);

        return [
            'periodo' => [
                'inicio' => $dataInicio,
                'fim' => $dataFim,
            ],
            'capital_inicial_brl' => round($valor, 2),
            'fatores' => [
                'ipca' => round($fatorIpca, 6),
                'cdi' => round($fatorCdi, 6),
                'usd' => round($fatorUsd, 6),
            ],
            'cenarios' => [
                'caixa' => $caixa,
                'cdi' => $cdi,
                'dolar' => $dolar,
            ],
            'veredito' => [
                'melhor_cenario' => $this->melhorCenario($caixa, $cdi, $dolar),
                'cdi_bateu_ipca' => $fatorCdi > $fatorIpca,
                'dolar_bateu_ipca' => $fatorUsd > $fatorIpca,
                'dolar_bateu_cdi' => $fatorUsd > $fatorCdi,
            ],
        ];
    }

    /**
     * @param  array<int, array{valor?: string|float}>|null  $serie
     */
    private function acumularFatorPercentual(?array $serie): float
    {
        if (! is_array($serie) || $serie === []) {
            throw new RuntimeException('Série do Banco Central vazia ou inválida.');
        }

        $fator = 1.0;

        foreach ($serie as $item) {
            if (! is_array($item) || ! isset($item['valor'])) {
                continue;
            }

            $fator *= (1 + ((float) str_replace(',', '.', (string) $item['valor']) / 100));
        }

        return $fator;
    }

    /**
     * @param  array<int, array{bid?: string|float}>|null  $cotacoes
     */
    private function calcularFatorUsd(?array $cotacoes): float
    {
        if (! is_array($cotacoes) || count($cotacoes) < 1) {
            throw new RuntimeException('Cotações USD/BRL vazias ou inválidas.');
        }

        // AwesomeAPI costuma retornar do mais recente para o mais antigo.
        $usdFim = (float) ($cotacoes[0]['bid'] ?? 0);
        $usdInicio = (float) ($cotacoes[count($cotacoes) - 1]['bid'] ?? 0);

        if ($usdInicio <= 0 || $usdFim <= 0) {
            throw new RuntimeException('Não foi possível calcular o fator USD/BRL.');
        }

        return $usdFim / $usdInicio;
    }

    /**
     * @return array{final_nominal: float, poder_compra: float, ganho_real: float}
     */
    private function montarCenario(float $capital, float $fatorAtivo, float $fatorIpca): array
    {
        $finalNominal = $capital * $fatorAtivo;
        $poderCompra = $fatorIpca > 0 ? $finalNominal / $fatorIpca : $finalNominal;

        return [
            'final_nominal' => round($finalNominal, 2),
            'poder_compra' => round($poderCompra, 2),
            'ganho_real' => round($poderCompra - $capital, 2),
        ];
    }

    /**
     * @param  array{ganho_real: float}  $caixa
     * @param  array{ganho_real: float}  $cdi
     * @param  array{ganho_real: float}  $dolar
     */
    private function melhorCenario(array $caixa, array $cdi, array $dolar): string
    {
        $cenarios = [
            'caixa' => $caixa['ganho_real'],
            'cdi' => $cdi['ganho_real'],
            'dolar' => $dolar['ganho_real'],
        ];

        arsort($cenarios);

        return (string) array_key_first($cenarios);
    }
}
