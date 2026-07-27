<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Empresa extends Model
{
    use HasFactory;

    protected $fillable = [
        'razao_social',
        'nome_fantasia',
        'cnpj',
        'email',
        'telefone',
        'endereco',
        'numero',
        'bairro',
        'cidade',
        'estado',
        'cep',
        'logo',
        'ativo',
        'uuid',
    ];

    protected $casts = [
        'ativo' => 'boolean',
    ];

    public function getTotalFuncionariosAttribute(): int
    {
        return $this->funcionarios()->count();
    }

    public function getTotalEquipamentosAttribute(): int
    {
        return $this->equipamentos()->count();
    }

    public function getTotalUsuariosAttribute(): int
    {
        return $this->usuarios()->count();
    }
    public function licenca(): HasOne
    {
        return $this->hasOne(Licenca::class);
    }
    public function usuarios(): HasMany
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

    public function equipamentos()
    {
        return $this->hasMany(Equipamento::class);
    }

    public function funcionarios()
    {
        return $this->hasMany(Funcionario::class);
    }

    public function agentes()
    {
        return $this->hasMany(Agente::class);
    }
}
