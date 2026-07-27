<?php

namespace App\Services\Ponto;

use App\Models\Equipamento;
use App\Models\MarcacaoRelogio;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use RuntimeException;
use Throwable;

class ImportadorCsvService
{
    private const COLUNAS_OBRIGATORIAS = [
        'codigo',
        'data_hora',
    ];

    public function __construct(
        private readonly ProcessadorMarcacaoService $processador
    ) {
    }

    /**
     * @return array{
     *     lidas: int,
     *     importadas: int,
     *     duplicadas: int,
     *     erros: int
     * }
     */
    public function importar(
        UploadedFile $arquivo,
        Equipamento $equipamento
    ): array {
        $caminho = $arquivo->getRealPath();

        if (! $caminho || ! is_readable($caminho)) {
            throw new RuntimeException(
                'Não foi possível acessar o arquivo enviado.'
            );
        }

        $handle = fopen($caminho, 'rb');

        if ($handle === false) {
            throw new RuntimeException(
                'Não foi possível abrir o arquivo.'
            );
        }

        $resultado = [
            'lidas' => 0,
            'importadas' => 0,
            'duplicadas' => 0,
            'erros' => 0,
        ];

        try {
            [$cabecalho, $delimitador] = $this->lerCabecalho($handle);

            while (
                ($linha = fgetcsv($handle, 0, $delimitador)) !== false
            ) {
                if ($this->linhaVazia($linha)) {
                    continue;
                }

                $resultado['lidas']++;

                if (count($linha) !== count($cabecalho)) {
                    $resultado['erros']++;

                    continue;
                }

                $dados = array_combine($cabecalho, $linha);

                if ($dados === false) {
                    $resultado['erros']++;

                    continue;
                }

                try {
                    $importada = $this->importarLinha(
                        $dados,
                        $equipamento
                    );

                    if ($importada) {
                        $resultado['importadas']++;
                    } else {
                        $resultado['duplicadas']++;
                    }
                } catch (Throwable $exception) {
                    report($exception);

                    $resultado['erros']++;
                }
            }
        } finally {
            fclose($handle);
        }

        $equipamento->forceFill([
            'ultima_sincronizacao_em' => now(),
            'ultima_mensagem' => json_encode(
                $resultado,
                JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR
            ),
        ])->save();

        return $resultado;
    }

    /**
     * @param resource $handle
     *
     * @return array{0: array<int, string>, 1: string}
     */
    private function lerCabecalho($handle): array
    {
        $primeiraLinha = fgets($handle);

        if ($primeiraLinha === false) {
            throw new RuntimeException(
                'O arquivo CSV está vazio.'
            );
        }

        $delimitador = $this->detectarDelimitador($primeiraLinha);
        $cabecalho = str_getcsv($primeiraLinha, $delimitador);

        $cabecalho = array_map(
            fn (mixed $valor): string => $this->normalizarCabecalho(
                (string) $valor
            ),
            $cabecalho
        );

        foreach (self::COLUNAS_OBRIGATORIAS as $coluna) {
            if (! in_array($coluna, $cabecalho, true)) {
                throw new RuntimeException(
                    'O CSV deve conter as colunas codigo e data_hora.'
                );
            }
        }

        if (count($cabecalho) !== count(array_unique($cabecalho))) {
            throw new RuntimeException(
                'O arquivo CSV contém colunas duplicadas.'
            );
        }

        return [$cabecalho, $delimitador];
    }

    private function detectarDelimitador(
        string $primeiraLinha
    ): string {
        $quantidadePontoVirgula = substr_count(
            $primeiraLinha,
            ';'
        );

        $quantidadeVirgula = substr_count(
            $primeiraLinha,
            ','
        );

        if (
            $quantidadePontoVirgula === 0
            && $quantidadeVirgula === 0
        ) {
            throw new RuntimeException(
                'Não foi possível identificar o delimitador do CSV.'
            );
        }

        return $quantidadePontoVirgula >= $quantidadeVirgula
            ? ';'
            : ',';
    }

    private function normalizarCabecalho(
        string $valor
    ): string {
        $valor = preg_replace(
            '/^\xEF\xBB\xBF/',
            '',
            $valor
        ) ?? $valor;

        return strtolower(trim($valor));
    }

    /**
     * @param array<string, mixed> $dados
     */
    private function importarLinha(
        array $dados,
        Equipamento $equipamento
    ): bool {
        $codigo = trim((string) ($dados['codigo'] ?? ''));

        if ($codigo === '') {
            throw new RuntimeException(
                'O código do funcionário não foi informado.'
            );
        }

        $valorDataHora = trim(
            (string) ($dados['data_hora'] ?? '')
        );

        if ($valorDataHora === '') {
            throw new RuntimeException(
                'A data e hora da marcação não foram informadas.'
            );
        }

        $timezone = $equipamento->timezone
            ?: 'America/Sao_Paulo';

        $dataHora = Carbon::parse(
            $valorDataHora,
            $timezone
        );

        $nsr = isset($dados['nsr'])
            ? trim((string) $dados['nsr'])
            : null;

        $nsr = $nsr !== '' ? $nsr : null;

        $hash = $this->gerarHash(
            $equipamento,
            $codigo,
            $dataHora,
            $nsr
        );

        return DB::transaction(function () use (
            $equipamento,
            $codigo,
            $dataHora,
            $nsr,
            $hash,
            $dados
        ): bool {
            $marcacao = MarcacaoRelogio::query()
                ->firstOrCreate(
                    [
                        'hash' => $hash,
                    ],
                    [
                        'empresa_id' => $equipamento->empresa_id,
                        'equipamento_id' => $equipamento->id,
                        'codigo_funcionario' => $codigo,
                        'data_hora' => $dataHora,
                        'nsr' => $nsr,
                        'dados_brutos' => $dados,
                    ]
                );

            if (! $marcacao->wasRecentlyCreated) {
                return false;
            }

            $this->processador->processar($marcacao);

            return true;
        });
    }

    private function gerarHash(
        Equipamento $equipamento,
        string $codigo,
        Carbon $dataHora,
        ?string $nsr
    ): string {
        return hash(
            'sha256',
            implode('|', [
                $equipamento->empresa_id,
                $equipamento->id,
                $codigo,
                $dataHora->format('Y-m-d H:i:s'),
                $nsr ?? '',
            ])
        );
    }

    /**
     * @param array<int, string|null> $linha
     */
    private function linhaVazia(array $linha): bool
    {
        foreach ($linha as $valor) {
            if (trim((string) $valor) !== '') {
                return false;
            }
        }

        return true;
    }
}
