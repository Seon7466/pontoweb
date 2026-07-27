<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    protected function casts(): array
    {
        return [
            'domingo' => 'boolean',
            'segunda' => 'boolean',
            'terca' => 'boolean',
            'quarta' => 'boolean',
            'quinta' => 'boolean',
            'sexta' => 'boolean',
            'sabado' => 'boolean',
            'ativo' => 'boolean',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function funcionarios(): HasMany
    {
        return $this->hasMany(Funcionario::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return $this->ativo
            ? 'Ativa'
            : 'Inativa';
    }

    public function getDiasTrabalhadosAttribute(): array
    {
        return collect([
            'Dom' => $this->domingo,
            'Seg' => $this->segunda,
            'Ter' => $this->terca,
            'Qua' => $this->quarta,
            'Qui' => $this->quinta,
            'Sex' => $this->sexta,
            'Sáb' => $this->sabado,
        ])
            ->filter()
            ->keys()
            ->values()
            ->all();
    }

    public function getDiasTrabalhadosLabelAttribute(): string
    {
        $dias = $this->dias_trabalhados;

        return empty($dias)
            ? '-'
            : implode(', ', $dias);
    }
}
