<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Escala extends Model
{
    use HasFactory;

    protected $table = 'escalas';

    protected $fillable = [
        'empresa_id',
        'descricao',
        'tipo',
        'domingo',
        'segunda',
        'terca',
        'quarta',
        'quinta',
        'sexta',
        'sabado',
        'ativo',
    ];

    protected $casts = [
        'domingo' => 'boolean',
        'segunda' => 'boolean',
        'terca' => 'boolean',
        'quarta' => 'boolean',
        'quinta' => 'boolean',
        'sexta' => 'boolean',
        'sabado' => 'boolean',
        'ativo' => 'boolean',
    ];

    /**
     * Empresa proprietária da escala.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Funcionários vinculados a esta escala.
     */
    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }
}
