<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MarcacaoRelogio extends Model
{
    protected $table = 'marcacoes_relogio';

    protected $fillable = [
        'empresa_id',
        'equipamento_id',
        'codigo_funcionario',
        'data_hora',
        'nsr',
        'hash',
        'dados_brutos',
        'status',
        'erro',
        'batida_ponto_id',
    ];

    protected function casts(): array
    {
        return [
            'data_hora' => 'datetime',
            'dados_brutos' => 'array',
        ];
    }

    public function equipamento(): BelongsTo
    {
        return $this->belongsTo(Equipamento::class);
    }

    public function batida(): BelongsTo
    {
        return $this->belongsTo(
            BatidaPonto::class,
            'batida_ponto_id'
        );
    }
}
