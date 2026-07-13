<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Equipamento extends Model
{
    protected $fillable = [
        'empresa_id', 'nome', 'fabricante', 'modelo', 'numero_serie', 'ip',
        'porta', 'protocolo', 'tipo_integracao', 'usuario_api', 'senha_api',
        'verificar_ssl', 'modo_671', 'timeout_segundos', 'timezone', 'ativo',
        'ultima_sincronizacao_em', 'ultimo_nsr', 'mac', 'versao_firmware',
        'ultima_conexao_em', 'ultima_falha_em', 'ultima_mensagem',
    ];

    protected $hidden = ['senha_api'];

    protected $casts = [
        'ativo' => 'boolean',
        'verificar_ssl' => 'boolean',
        'modo_671' => 'boolean',
        'senha_api' => 'encrypted',
        'ultima_sincronizacao_em' => 'datetime',
        'ultima_conexao_em' => 'datetime',
        'ultima_falha_em' => 'datetime',
    ];

    public function empresa() { return $this->belongsTo(Empresa::class); }
    public function marcacoes() { return $this->hasMany(MarcacaoRelogio::class); }

    public function baseUrl(): string
    {
        $protocolo = $this->protocolo ?: 'https';
        $porta = $this->porta ?: ($protocolo === 'https' ? 443 : 80);

        return sprintf('%s://%s:%d', $protocolo, $this->ip, $porta);
    }

    public function isControlIdClass(): bool
    {
        return str_contains(strtolower((string) $this->fabricante), 'control')
            || str_contains(strtolower((string) $this->modelo), 'idclass');
    }
}
