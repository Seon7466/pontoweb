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
        'armazenamento_gb',
        'suporte',
        'api_disponivel',
        'app_mobile',
        'bi_disponivel',
        'geolocalizacao',
        'integracoes',
        'backup_automatico',
        'marketplace',
        'recursos',
        'ordem',
        'ativo',
    ];

    protected function casts(): array
    {
        return [
            'valor_mensal' => 'decimal:2',
            'api_disponivel' => 'boolean',
            'app_mobile' => 'boolean',
            'bi_disponivel' => 'boolean',
            'geolocalizacao' => 'boolean',
            'integracoes' => 'boolean',
            'backup_automatico' => 'boolean',
            'marketplace' => 'boolean',
            'recursos' => 'array',
            'ativo' => 'boolean',
        ];
    }

    public function getLimiteFuncionariosLabelAttribute(): string
    {
        return $this->max_funcionarios === null ? 'Ilimitado' : (string) $this->max_funcionarios;
    }

    public function getLimiteRelogiosLabelAttribute(): string
    {
        return $this->max_relogios === null ? 'Ilimitado' : (string) $this->max_relogios;
    }

    public function getValorLabelAttribute(): string
    {
        return $this->valor_mensal === null
            ? 'Sob consulta'
            : 'R$ '.number_format((float) $this->valor_mensal, 2, ',', '.');
    }
}
