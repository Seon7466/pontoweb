<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('funcionarios', function (Blueprint $table) {
            $table->string('codigo_relogio')->nullable()->after('matricula');
            $table->index(['empresa_id', 'codigo_relogio']);
        });

        Schema::table('equipamentos', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->string('nome')->nullable()->after('empresa_id');
            $table->string('fabricante')->nullable()->after('nome');
            $table->string('modelo')->nullable()->after('fabricante');
            $table->string('numero_serie')->nullable()->after('modelo');
            $table->string('ip')->nullable()->after('numero_serie');
            $table->unsignedSmallInteger('porta')->nullable()->after('ip');
            $table->string('tipo_integracao')->default('arquivo')->after('porta');
            $table->string('timezone')->default('America/Sao_Paulo')->after('tipo_integracao');
            $table->boolean('ativo')->default(true)->after('timezone');
            $table->timestamp('ultima_sincronizacao_em')->nullable()->after('ativo');
            $table->text('ultima_mensagem')->nullable()->after('ultima_sincronizacao_em');
            $table->index(['empresa_id', 'ativo']);
        });

        Schema::table('batida_pontos', function (Blueprint $table) {
            $table->foreignId('empresa_id')->nullable()->after('id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('equipamento_id')->nullable()->after('funcionario_id')->constrained('equipamentos')->nullOnDelete();
            $table->string('identificador_externo')->nullable()->after('origem');
            $table->string('hash_importacao', 64)->nullable()->after('identificador_externo');
            $table->string('user_agent')->nullable()->after('ip');
            $table->boolean('manual')->default(false)->after('observacao');
            $table->foreignId('registrado_por')->nullable()->after('manual')->constrained('users')->nullOnDelete();
            $table->index(['empresa_id', 'data']);
            $table->unique('hash_importacao');
        });

        Schema::create('marcacoes_relogio', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
            $table->foreignId('equipamento_id')->nullable()->constrained('equipamentos')->nullOnDelete();
            $table->string('codigo_funcionario');
            $table->dateTime('data_hora');
            $table->string('nsr')->nullable();
            $table->string('hash', 64)->unique();
            $table->json('dados_brutos')->nullable();
            $table->string('status')->default('pendente');
            $table->text('erro')->nullable();
            $table->foreignId('batida_ponto_id')->nullable()->constrained('batida_pontos')->nullOnDelete();
            $table->timestamps();
            $table->index(['empresa_id', 'status']);
            $table->index(['empresa_id', 'data_hora']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('marcacoes_relogio');

        Schema::table('batida_pontos', function (Blueprint $table) {
            $table->dropUnique(['hash_importacao']);
            $table->dropConstrainedForeignId('registrado_por');
            $table->dropConstrainedForeignId('equipamento_id');
            $table->dropConstrainedForeignId('empresa_id');
            $table->dropColumn(['identificador_externo', 'hash_importacao', 'user_agent', 'manual']);
        });

        Schema::table('equipamentos', function (Blueprint $table) {
            $table->dropConstrainedForeignId('empresa_id');
            $table->dropColumn(['nome', 'fabricante', 'modelo', 'numero_serie', 'ip', 'porta', 'tipo_integracao', 'timezone', 'ativo', 'ultima_sincronizacao_em', 'ultima_mensagem']);
        });

        Schema::table('funcionarios', function (Blueprint $table) {
            $table->dropIndex(['empresa_id', 'codigo_relogio']);
            $table->dropColumn('codigo_relogio');
        });
    }
};
