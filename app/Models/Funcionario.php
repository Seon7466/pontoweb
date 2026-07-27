<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'status',
    ];

    protected function casts(): array
    {
        return [
            'nascimento' => 'date',
            'admissao' => 'date',
            'demissao' => 'date',
            'status' => 'boolean',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function departamento(): BelongsTo
    {
        return $this->belongsTo(Departamento::class);
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class);
    }

    public function horario(): BelongsTo
    {
        return $this->belongsTo(Horario::class);
    }

    public function escala(): BelongsTo
    {
        return $this->belongsTo(Escala::class);
    }

    public function batidas(): HasMany
    {
        return $this->hasMany(BatidaPonto::class);
    }

    public function horasExtras(): HasMany
    {
        return $this->hasMany(HoraExtra::class);
    }

    public function getAtivoAttribute(): bool
    {
        return (bool) $this->status;
    }

    public function getDesligadoAttribute(): bool
    {
        return ! $this->status || $this->demissao !== null;
    }

    public function getNomeCompletoAttribute(): string
    {
        return trim($this->nome);
    }
}
