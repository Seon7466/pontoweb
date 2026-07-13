<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BancoHora extends Model
{
    use HasFactory;

    protected $table = 'banco_horas';

    protected $fillable = [
        'funcionario_id',
        'data',
        'minutos',
        'tipo',
        'motivo',
    ];

    protected $casts = [
        'data' => 'date',
    ];

    public function funcionario()
    {
        return $this->belongsTo(Funcionario::class);
    }
    public function bancoHoras()
{
    return $this->hasMany(BancoHora::class);
}
}
