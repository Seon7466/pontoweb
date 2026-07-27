<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BancoHora extends Model
{
    protected $table = 'banco_horas';

    protected $fillable = [
        'funcionario_id',
        'data',
        'minutos',
        'tipo',
        'motivo',
        'origem',
        'referencia_tipo',
        'referencia_id',
        'observacao',
        'registrado_por',
    ];

    protected $casts = [
        'data' => 'date',
        'minutos' => 'integer',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function registradoPor()
    {
        return $this->belongsTo(User::class, 'registrado_por');
    }
}