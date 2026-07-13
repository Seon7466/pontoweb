<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plano extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
        'descricao',
        'max_funcionarios',
        'max_relogios',
        'valor_mensal',
        'ativo',
    ];

    protected $casts = [
        'valor_mensal' => 'decimal:2',
        'ativo' => 'boolean',
    ];
}
