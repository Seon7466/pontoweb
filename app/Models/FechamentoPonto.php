<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FechamentoPonto extends Model
{
    protected $fillable = [
        'empresa_id',
        'ano',
        'mes',
        'fechado_em',
        'user_id',
    ];

    protected $casts = [
        'fechado_em' => 'datetime',
    ];

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}