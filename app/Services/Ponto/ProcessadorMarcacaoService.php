<?php

namespace App\Services\Ponto;

use App\Models\BatidaPonto;
use App\Models\Funcionario;
use App\Models\MarcacaoRelogio;
use Illuminate\Support\Facades\DB;

class ProcessadorMarcacaoService
{
    public function processar(MarcacaoRelogio $marcacao): MarcacaoRelogio
    {
        return DB::transaction(function () use ($marcacao) {
            $funcionario = Funcionario::query()
                ->where('empresa_id', $marcacao->empresa_id)
                ->where(function ($query) use ($marcacao) {
                    $query->where('codigo_relogio', $marcacao->codigo_funcionario)
                        ->orWhere('matricula', $marcacao->codigo_funcionario)
                        ->orWhere('pis', $marcacao->codigo_funcionario);
                })
                ->first();

            if (! $funcionario) {
                $marcacao->update(['status' => 'erro', 'erro' => 'Funcionário não localizado pelo código, matrícula ou PIS.']);
                return $marcacao;
            }

            $hash = hash('sha256', implode('|', [
                $marcacao->empresa_id,
                $marcacao->equipamento_id,
                $marcacao->codigo_funcionario,
                $marcacao->data_hora->format('Y-m-d H:i:s'),
                $marcacao->nsr,
            ]));

            $quantidadeNoDia = BatidaPonto::query()
                ->where('empresa_id', $marcacao->empresa_id)
                ->where('funcionario_id', $funcionario->id)
                ->whereDate('data', $marcacao->data_hora->toDateString())
                ->count();

            $tipos = ['entrada', 'saida_intervalo', 'retorno_intervalo', 'saida'];
            $tipo = $tipos[min($quantidadeNoDia, 3)];

            $batida = BatidaPonto::firstOrCreate(
                ['hash_importacao' => $hash],
                [
                    'empresa_id' => $marcacao->empresa_id,
                    'funcionario_id' => $funcionario->id,
                    'equipamento_id' => $marcacao->equipamento_id,
                    'data' => $marcacao->data_hora->toDateString(),
                    'data_hora' => $marcacao->data_hora,
                    'tipo' => $tipo,
                    'origem' => 'relogio_biometrico',
                    'identificador_externo' => $marcacao->nsr,
                    'observacao' => 'Importada automaticamente do equipamento.',
                    'manual' => false,
                ]
            );

            $marcacao->update([
                'status' => 'processada',
                'erro' => null,
                'batida_ponto_id' => $batida->id,
            ]);

            return $marcacao->refresh();
        });
    }
}
