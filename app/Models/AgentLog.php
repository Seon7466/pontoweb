<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AgentLog extends Model
{
    protected $fillable = ['agent_id', 'empresa_id', 'level', 'event', 'message', 'context', 'occurred_at'];

    protected function casts(): array
    {
        return ['context' => 'array', 'occurred_at' => 'datetime'];
    }

    public function agente(): BelongsTo { return $this->belongsTo(Agente::class, 'agent_id'); }
    public function empresa(): BelongsTo { return $this->belongsTo(Empresa::class); }
}
