<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('agentes')) {
            Schema::create('agentes', function (Blueprint $table) {
                $table->id();
                $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
                $table->foreignId('licenca_id')->constrained('licencas')->cascadeOnDelete();
                $table->uuid('installation_id');
                $table->string('nome')->nullable();
                $table->string('machine_name');
                $table->string('agent_version', 40)->nullable();
                $table->string('os_name')->nullable();
                $table->string('os_version')->nullable();
                $table->string('local_ip', 45)->nullable();
                $table->string('public_ip', 45)->nullable();
                $table->string('status', 20)->default('offline')->index();
                $table->timestamp('last_seen_at')->nullable()->index();
                $table->timestamp('last_sync_at')->nullable();
                $table->json('metadata')->nullable();
                $table->timestamps();

                $table->unique(['licenca_id', 'installation_id']);
                $table->index(['empresa_id', 'last_seen_at']);
            });
        }

        if (! Schema::hasTable('agent_logs')) {
            Schema::create('agent_logs', function (Blueprint $table) {
                $table->id();
                $table->foreignId('agent_id')->constrained('agentes')->cascadeOnDelete();
                $table->foreignId('empresa_id')->constrained('empresas')->cascadeOnDelete();
                $table->string('level', 20)->default('info')->index();
                $table->string('event', 60)->index();
                $table->text('message');
                $table->json('context')->nullable();
                $table->timestamp('occurred_at')->index();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('agent_logs');
        Schema::dropIfExists('agentes');
    }
};
