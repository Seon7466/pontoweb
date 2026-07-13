<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'inscricao_estadual',
        'telefone',
        'email',
        'cep',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'ativo',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function departamentos()
    {
        return $this->hasMany(Departamento::class);
    }

    public function cargos()
    {
        return $this->hasMany(Cargo::class);
    }

    public function horarios()
    {
        return $this->hasMany(Horario::class);
    }

    public function escalas()
    {
        return $this->hasMany(Escala::class);
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }
}
