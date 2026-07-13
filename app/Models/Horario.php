<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios';

    protected $fillable = [
        'empresa_id',
        'descricao',
        'entrada',
        'saida',
        'inicio_intervalo',
        'fim_intervalo',
        'tolerancia_entrada',
        'tolerancia_saida',
    ];

    /**
     * Empresa proprietária do horário.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Funcionários vinculados a este horário.
     */
    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }
}
