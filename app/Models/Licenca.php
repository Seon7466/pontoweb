<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Licenca extends Model
{
    use HasFactory;

    public const STATUS_TESTE = 'teste';
    public const STATUS_ATIVA = 'ativa';
    public const STATUS_SUSPENSA = 'suspensa';
    public const STATUS_VENCIDA = 'vencida';
    public const STATUS_CANCELADA = 'cancelada';

    protected $fillable = [
        'empresa_id', 'plano_id', 'codigo', 'status', 'ciclo_cobranca',
        'inicia_em', 'termina_em', 'periodo_teste_ate', 'limite_funcionarios',
        'limite_relogios', 'valor_contratado', 'renovacao_automatica',
        'agent_token_prefix', 'agent_token_hash', 'agent_token_rotacionado_em',
        'suspensa_em', 'cancelada_em', 'observacoes',
    ];

    protected $hidden = ['agent_token_hash'];

    protected function casts(): array
    {
        return [
            'inicia_em' => 'date',
            'termina_em' => 'date',
            'periodo_teste_ate' => 'date',
            'valor_contratado' => 'decimal:2',
            'renovacao_automatica' => 'boolean',
            'agent_token_rotacionado_em' => 'datetime',
            'suspensa_em' => 'datetime',
            'cancelada_em' => 'datetime',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function plano(): BelongsTo
    {
        return $this->belongsTo(Plano::class);
    }

    public function historicos(): HasMany
    {
        return $this->hasMany(LicencaHistorico::class)->latest();
    }

    public function agentes(): HasMany
    {
        return $this->hasMany(Agente::class);
    }

    public function getStatusEfetivoAttribute(): string
    {
        if (in_array($this->status, [self::STATUS_SUSPENSA, self::STATUS_CANCELADA], true)) {
            return $this->status;
        }

        if ($this->termina_em?->isPast()) {
            return self::STATUS_VENCIDA;
        }

        if ($this->status === self::STATUS_TESTE && $this->periodo_teste_ate?->isPast()) {
            return self::STATUS_VENCIDA;
        }

        return $this->status;
    }

    public function getValidaAttribute(): bool
    {
        return in_array($this->status_efetivo, [self::STATUS_ATIVA, self::STATUS_TESTE], true)
            && ! $this->inicia_em?->isFuture();
    }

    public function getLimiteFuncionariosEfetivoAttribute(): ?int
    {
        return $this->limite_funcionarios ?? $this->plano?->max_funcionarios;
    }

    public function getLimiteRelogiosEfetivoAttribute(): ?int
    {
        return $this->limite_relogios ?? $this->plano?->max_relogios;
    }

    public function getValorEfetivoAttribute(): ?string
    {
        return $this->valor_contratado ?? $this->plano?->valor_mensal;
    }

    public function getValorLabelAttribute(): string
    {
        return $this->valor_efetivo === null
            ? 'Sob consulta'
            : 'R$ '.number_format((float) $this->valor_efetivo, 2, ',', '.');
    }

    public function getStatusLabelAttribute(): string
    {
        return [
            self::STATUS_TESTE => 'Em teste',
            self::STATUS_ATIVA => 'Ativa',
            self::STATUS_SUSPENSA => 'Suspensa',
            self::STATUS_VENCIDA => 'Vencida',
            self::STATUS_CANCELADA => 'Cancelada',
        ][$this->status_efetivo] ?? ucfirst($this->status_efetivo);
    }

    public function getDiasParaVencimentoAttribute(): ?int
    {
        return $this->termina_em?->diffInDays(now(), false) * -1;
    }
}
