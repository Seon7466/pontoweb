<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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

    protected $casts = [
        'data' => 'date',
        'pago' => 'boolean',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
}
