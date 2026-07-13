<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BatidaPonto extends Model
{
    use HasFactory;

    protected $table = 'batida_pontos';

    protected $fillable = [
        'empresa_id',
        'funcionario_id',
        'equipamento_id',
        'data',
        'data_hora',
        'tipo',
        'origem',
        'identificador_externo',
        'hash_importacao',
        'ip',
        'user_agent',
        'latitude',
        'longitude',
        'observacao',
        'manual',
        'registrado_por',
    ];

    protected $casts = [
        'data' => 'date',
        'data_hora' => 'datetime',
        'manual' => 'boolean',
    ];

    public function empresa() { return $this->belongsTo(Empresa::class); }

    public function equipamento() { return $this->belongsTo(Equipamento::class); }

    public function usuarioRegistro() { return $this->belongsTo(User::class, 'registrado_por'); }

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}
