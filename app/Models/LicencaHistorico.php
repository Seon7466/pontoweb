<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LicencaHistorico extends Model
{
    protected $fillable = ['licenca_id', 'user_id', 'tipo', 'descricao', 'dados'];

    protected function casts(): array
    {
        return ['dados' => 'array'];
    }

    public function licenca(): BelongsTo
    {
        return $this->belongsTo(Licenca::class);
    }

    public function usuario(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
