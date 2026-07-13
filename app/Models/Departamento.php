<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departamento extends Model
{
    use HasFactory;

    protected $table = 'departamentos';

    protected $fillable = [
        'empresa_id',
        'nome',
        'responsavel',
        'ativo'
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    /**
     * Empresa à qual o departamento pertence.
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    /**
     * Cargos vinculados ao departamento.
     */
    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }

    /**
     * Funcionários vinculados ao departamento.
     */
    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }
}
