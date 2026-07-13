<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ferias extends Model
{
    use HasFactory;

    protected $table = 'ferias';

    protected $fillable = [
        'funcionario_id',
        'periodo_aquisitivo_inicio',
        'periodo_aquisitivo_fim',
        'inicio',
        'fim',
        'dias',
        'vendidas',
        'dias_vendidos',
        'data_pagamento',
        'status',
        'observacao'
    ];

    protected $casts = [
        'periodo_aquisitivo_inicio' => 'date',
        'periodo_aquisitivo_fim' => 'date',
        'inicio' => 'date',
        'fim' => 'date',
        'data_pagamento' => 'date',
        'vendidas' => 'boolean',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
    public function ferias()
    {
        return $this->hasMany(Ferias::class);
    }
}
