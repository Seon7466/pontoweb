<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agente extends Model
{
    protected $fillable = [
        'empresa_id',
        'licenca_id',
        'installation_id',
        'nome',
        'machine_name',
        'agent_version',
        'os_name',
        'os_version',
        'local_ip',
        'public_ip',
        'status',
        'last_seen_at',
        'last_sync_at',
        'metadata',
    ];

    protected function casts(): array
    {
        return [
            'last_seen_at' => 'datetime',
            'last_sync_at' => 'datetime',
            'metadata' => 'array',
        ];
    }

    public function empresa(): BelongsTo
    {
        return $this->belongsTo(Empresa::class);
    }

    public function licenca(): BelongsTo
    {
        return $this->belongsTo(Licenca::class);
    }

    public function logs(): HasMany
    {
        return $this->hasMany(AgentLog::class);
    }

    public function getOnlineAttribute(): bool
    {
        if ($this->last_seen_at === null) {
            return false;
        }

        return $this->last_seen_at->greaterThanOrEqualTo(
            now()->subMinutes(5)
        );
    }

    public function getStatusEfetivoAttribute(): string
    {
        return $this->online
            ? 'online'
            : 'offline';
    }

    public function getUltimoContatoLabelAttribute(): string
    {
        if ($this->last_seen_at === null) {
            return 'Nunca conectado';
        }

        return $this->last_seen_at->diffForHumans();
    }

    public function getUltimaSincronizacaoLabelAttribute(): string
    {
        if ($this->last_sync_at === null) {
            return 'Nunca sincronizado';
        }

        return $this->last_sync_at->diffForHumans();
    }

    public function getVersaoAtualizadaAttribute(): bool
    {
        return $this->agent_version === config(
            'app.agent_latest_version',
            '0.1.0'
        );
    }
}
