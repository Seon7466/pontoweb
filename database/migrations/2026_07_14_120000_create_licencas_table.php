<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('licencas')) {
            Schema::create('licencas', function (Blueprint $table) {
                $table->id();
                $table->foreignId('empresa_id')->unique()->constrained('empresas')->cascadeOnDelete();
                $table->foreignId('plano_id')->constrained('planos')->restrictOnDelete();
                $table->string('codigo', 40)->unique();
                $table->string('status', 20)->default('teste')->index();
                $table->string('ciclo_cobranca', 20)->default('mensal');
                $table->date('inicia_em');
                $table->date('termina_em')->nullable()->index();
                $table->date('periodo_teste_ate')->nullable();
                $table->unsignedInteger('limite_funcionarios')->nullable();
                $table->unsignedInteger('limite_relogios')->nullable();
                $table->decimal('valor_contratado', 10, 2)->nullable();
                $table->boolean('renovacao_automatica')->default(false);
                $table->string('agent_token_prefix', 16)->nullable()->index();
                $table->string('agent_token_hash', 64)->nullable();
                $table->timestamp('agent_token_rotacionado_em')->nullable();
                $table->timestamp('suspensa_em')->nullable();
                $table->timestamp('cancelada_em')->nullable();
                $table->text('observacoes')->nullable();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('licenca_historicos')) {
            Schema::create('licenca_historicos', function (Blueprint $table) {
                $table->id();
                $table->foreignId('licenca_id')->constrained('licencas')->cascadeOnDelete();
                $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->string('tipo', 40)->index();
                $table->string('descricao');
                $table->json('dados')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('licenca_historicos');
        Schema::dropIfExists('licencas');
    }
};
