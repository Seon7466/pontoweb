<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class);
    }

    public function getCargaHorariaLabelAttribute(): string
    {
        if (! $this->entrada || ! $this->saida) {
            return '-';
        }

        return "{$this->entrada} às {$this->saida}";
    }
}
