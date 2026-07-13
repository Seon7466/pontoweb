<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cargo extends Model
{
    use HasFactory;

    protected $table = 'cargos';

    protected $fillable = [
        'empresa_id',
        'departamento_id',
        'nome',
        'cbo',
        'descricao',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean'
    ];

    /**
     * Relacionamento com Empresa
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Relacionamento com Departamento
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    /**
     * Funcionários desse cargo
     */
    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }
}
