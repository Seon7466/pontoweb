<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HoraExtra extends Model
{
    use HasFactory;

    protected $table = 'hora_extras';

    protected $fillable = [
        'funcionario_id',
        'data',
        'minutos',
        'percentual',
        'pago',
        'observacao',
    ];

    protected function casts(): array
    {
        return [
            'data' => 'date',
            'pago' => 'boolean',
        ];
    }

    public function funcionario(): BelongsTo
    {
        return $this->belongsTo(Funcionario::class);
    }

    public function getHorasFormatadasAttribute(): string
    {
        $horas = intdiv($this->minutos, 60);
        $minutos = $this->minutos % 60;

        return sprintf('%02d:%02d', $horas, $minutos);
    }

    public function getStatusPagamentoAttribute(): string
    {
        return $this->pago
            ? 'Pago'
            : 'Pendente';
    }

    public function getPercentualFormatadoAttribute(): string
    {
        return "{$this->percentual}%";
    }
}
