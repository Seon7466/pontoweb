<?php

namespace App\Services\Ponto;

use App\Models\Equipamento;
use App\Models\MarcacaoRelogio;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class ImportadorCsvService
{
    public function __construct(private ProcessadorMarcacaoService $processador) {}

    public function importar(UploadedFile $arquivo, Equipamento $equipamento): array
    {
        $handle = fopen($arquivo->getRealPath(), 'rb');
        if (! $handle) throw new RuntimeException('Não foi possível abrir o arquivo.');

        $cabecalho = fgetcsv($handle, 0, ';');
        if (! $cabecalho || count($cabecalho) === 1) {
            rewind($handle);
            $cabecalho = fgetcsv($handle, 0, ',');
            $delimitador = ',';
        } else {
            $delimitador = ';';
        }

        $cabecalho = array_map(fn ($v) => strtolower(trim((string) $v)), $cabecalho ?: []);
        foreach (['codigo', 'data_hora'] as $obrigatorio) {
            if (! in_array($obrigatorio, $cabecalho, true)) {
                fclose($handle);
                throw new RuntimeException("O CSV deve conter as colunas codigo e data_hora.");
            }
        }

        $resultado = ['lidas' => 0, 'importadas' => 0, 'duplicadas' => 0, 'erros' => 0];

        while (($linha = fgetcsv($handle, 0, $delimitador)) !== false) {
            if (count($linha) !== count($cabecalho)) { $resultado['erros']++; continue; }
            $dados = array_combine($cabecalho, $linha);
            $resultado['lidas']++;

            try {
                $dataHora = Carbon::parse(trim($dados['data_hora']), $equipamento->timezone ?: 'America/Sao_Paulo');
                $codigo = trim($dados['codigo']);
                $nsr = isset($dados['nsr']) ? trim($dados['nsr']) : null;
                $hash = hash('sha256', implode('|', [$equipamento->empresa_id, $equipamento->id, $codigo, $dataHora->format('Y-m-d H:i:s'), $nsr]));

                if (MarcacaoRelogio::where('hash', $hash)->exists()) { $resultado['duplicadas']++; continue; }

                $marcacao = DB::transaction(fn () => MarcacaoRelogio::create([
                    'empresa_id' => $equipamento->empresa_id,
                    'equipamento_id' => $equipamento->id,
                    'codigo_funcionario' => $codigo,
                    'data_hora' => $dataHora,
                    'nsr' => $nsr,
                    'hash' => $hash,
                    'dados_brutos' => $dados,
                ]));

                $this->processador->processar($marcacao);
                $resultado['importadas']++;
            } catch (\Throwable $e) {
                $resultado['erros']++;
            }
        }

        fclose($handle);
        $equipamento->update(['ultima_sincronizacao_em' => now(), 'ultima_mensagem' => json_encode($resultado)]);
        return $resultado;
    }
}
