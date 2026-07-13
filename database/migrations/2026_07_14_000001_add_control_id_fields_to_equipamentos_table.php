<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->string('protocolo')->default('https')->after('porta');
            $table->string('usuario_api')->nullable()->after('tipo_integracao');
            $table->text('senha_api')->nullable()->after('usuario_api');
            $table->boolean('verificar_ssl')->default(false)->after('senha_api');
            $table->boolean('modo_671')->default(true)->after('verificar_ssl');
            $table->unsignedInteger('timeout_segundos')->default(15)->after('modo_671');
            $table->unsignedBigInteger('ultimo_nsr')->nullable()->after('ultima_sincronizacao_em');
            $table->string('mac')->nullable()->after('ultimo_nsr');
            $table->string('versao_firmware')->nullable()->after('mac');
            $table->timestamp('ultima_conexao_em')->nullable()->after('versao_firmware');
            $table->timestamp('ultima_falha_em')->nullable()->after('ultima_conexao_em');
        });
    }

    public function down(): void
    {
        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropColumn([
                'protocolo', 'usuario_api', 'senha_api', 'verificar_ssl', 'modo_671',
                'timeout_segundos', 'ultimo_nsr', 'mac', 'versao_firmware',
                'ultima_conexao_em', 'ultima_falha_em',
            ]);
        });
    }
};
