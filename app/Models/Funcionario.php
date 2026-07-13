<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Funcionario extends Model
{
    use HasFactory;

    protected $table = 'funcionarios';

    protected $fillable = [
        'empresa_id',
        'departamento_id',
        'cargo_id',
        'horario_id',
        'escala_id',
        'nome',
        'cpf',
        'rg',
        'pis',
        'matricula',
        'codigo_relogio',
        'nascimento',
        'admissao',
        'demissao',
        'email',
        'telefone',
        'foto',
        'status'
    ];

    protected $casts = [
        'admissao' => 'date',
        'demissao' => 'date',
        'nascimento' => 'date'
    ];

    /**
     * RELACIONAMENTOS
     */

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function departamento()
    {
        return $this->belongsTo(Departamento::class);
    }

    public function cargo()
    {
        return $this->belongsTo(Cargo::class);
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class);
    }

    public function escala()
    {
        return $this->belongsTo(Escala::class);
    }

    public function batidas()
    {
        return $this->hasMany(BatidaPonto::class);
    }
    public function horasExtras()
    {
        return $this->hasMany(HoraExtra::class);
    }
}
