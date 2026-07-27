<?php

namespace App\Services\Relogios\ControlId;

use App\Models\Equipamento;
use App\Models\MarcacaoRelogio;
use App\Services\Ponto\ProcessadorMarcacaoService;
use Carbon\CarbonInterface;
use Throwable;

class ControlIdSyncService
{
    public function __construct(
        private readonly ControlIdClassAdapter $adapter,
        private readonly ProcessadorMarcacaoService $processador,
    ) {
    }

    /**
     * @return array{
     *     recebidas: int,
     *     novas: int,
     *     duplicadas: int,
     *     processadas: int,
     *     erros: int
     * }
     */
    public function sincronizar(
        Equipamento $equipamento,
        bool $processar = true
    ): array {
        $resultado = [
            'recebidas' => 0,
            'novas' => 0,
            'duplicadas' => 0,
            'processadas' => 0,
            'erros' => 0,
        ];

        $maiorNsr = (int) ($equipamento->ultimo_nsr ?? 0);

        try {
            foreach (
                $this->adapter->buscarMarcacoes($equipamento)
                as $registro
            ) {
                $resultado['recebidas']++;

                try {
                    $marcacao = $this->salvarMarcacao(
                        $equipamento,
                        $registro
                    );

                    if ($marcacao->wasRecentlyCreated) {
                        $resultado['novas']++;
                    } else {
                        $resultado['duplicadas']++;
                    }

                    if (
                        $processar
                        && $marcacao->status === 'pendente'
                    ) {
                        $marcacao = $this->processador->processar(
                            $marcacao
                        );

                        if ($marcacao->status === 'processada') {
                            $resultado['processadas']++;
                        } else {
                            $resultado['erros']++;
                        }
                    }

                    $maiorNsr = $this->atualizarMaiorNsr(
                        $maiorNsr,
                        $registro['nsr'] ?? null
                    );
                } catch (Throwable $exception) {
                    report($exception);

                    $resultado['erros']++;
                }
            }

            $agora = now();

            $equipamento->forceFill([
                'ultimo_nsr' => $maiorNsr ?: null,
                'ultima_sincronizacao_em' => $agora,
                'ultima_conexao_em' => $agora,
                'ultima_falha_em' => null,
                'ultima_mensagem' => $this->montarMensagem($resultado),
            ])->save();

            return $resultado;
        } catch (Throwable $exception) {
            report($exception);

            $equipamento->forceFill([
                'ultima_falha_em' => now(),
                'ultima_mensagem' => 'Falha ao consultar o equipamento.',
            ])->save();

            throw $exception;
        }
    }

    /**
     * @param array<string, mixed> $registro
     */
    private function salvarMarcacao(
        Equipamento $equipamento,
        array $registro
    ): MarcacaoRelogio {
        $codigo = trim((string) ($registro['codigo'] ?? ''));
        $dataHora = $registro['data_hora'] ?? null;

        if ($codigo === '') {
            throw new \RuntimeException(
                'A marcação não possui código de funcionário.'
            );
        }

        if (! $dataHora instanceof CarbonInterface) {
            throw new \RuntimeException(
                'A marcação não possui uma data e hora válidas.'
            );
        }

        $nsr = isset($registro['nsr'])
            ? trim((string) $registro['nsr'])
            : null;

        $nsr = $nsr !== '' ? $nsr : null;

        $hash = $this->gerarHash(
            $equipamento,
            $codigo,
            $dataHora,
            $nsr
        );

        return MarcacaoRelogio::query()->firstOrCreate(
            [
                'hash' => $hash,
            ],
            [
                'empresa_id' => $equipamento->empresa_id,
                'equipamento_id' => $equipamento->id,
                'codigo_funcionario' => $codigo,
                'data_hora' => $dataHora,
                'nsr' => $nsr,
                'dados_brutos' => $registro['dados_brutos'] ?? $registro,
                'status' => 'pendente',
            ]
        );
    }

    private function gerarHash(
        Equipamento $equipamento,
        string $codigo,
        CarbonInterface $dataHora,
        ?string $nsr
    ): string {
        $dataHoraUtc = $dataHora
            ->copy()
            ->utc()
            ->format('Y-m-d H:i:s');

        return hash(
            'sha256',
            implode('|', [
                $equipamento->empresa_id,
                $equipamento->id,
                $codigo,
                $dataHoraUtc,
                $nsr ?? '',
            ])
        );
    }

    private function atualizarMaiorNsr(
        int $maiorNsr,
        mixed $nsr
    ): int {
        if (! is_numeric($nsr)) {
            return $maiorNsr;
        }

        return max($maiorNsr, (int) $nsr);
    }

    /**
     * @param array{
     *     recebidas: int,
     *     novas: int,
     *     duplicadas: int,
     *     processadas: int,
     *     erros: int
     * } $resultado
     */
    private function montarMensagem(array $resultado): string
    {
        return sprintf(
            'Sincronização concluída: %d recebidas, %d novas, %d duplicadas, %d processadas e %d erros.',
            $resultado['recebidas'],
            $resultado['novas'],
            $resultado['duplicadas'],
            $resultado['processadas'],
            $resultado['erros']
        );
    }
}
