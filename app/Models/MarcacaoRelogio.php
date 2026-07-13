<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarcacaoRelogio extends Model
{
    protected $table = 'marcacoes_relogio';

    protected $fillable = [
        'empresa_id', 'equipamento_id', 'codigo_funcionario', 'data_hora',
        'nsr', 'hash', 'dados_brutos', 'status', 'erro', 'batida_ponto_id',
    ];

    protected $casts = [
        'data_hora' => 'datetime',
        'dados_brutos' => 'array',
    ];

    public function equipamento() { return $this->belongsTo(Equipamento::class); }
    public function batida() { return $this->belongsTo(BatidaPonto::class, 'batida_ponto_id'); }
}
