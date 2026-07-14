<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
{
    // Nos testes com SQLite a tabela ainda não existe neste momento.
    if (! Schema::hasTable('agent_logs')) {
        return;
    }

    if (! Schema::hasColumn('agent_logs', 'agente_id')) {
        Schema::table('agent_logs', function (Blueprint $table) {
            $table->foreignId('agente_id')
                ->nullable()
                ->after('id')
                ->constrained('agentes')
                ->cascadeOnDelete();
        });
    }

    // Compatibilidade com versões antigas
    if (
        Schema::hasColumn('agent_logs', 'agent_id') &&
        Schema::hasColumn('agent_logs', 'agente_id')
    ) {
        DB::table('agent_logs')
            ->whereNull('agente_id')
            ->update([
                'agente_id' => DB::raw('agent_id'),
            ]);
    }
}

    public function down(): void
    {
        if (Schema::hasColumn('agent_logs', 'agente_id')) {
            Schema::table('agent_logs', function (Blueprint $table) {
                $table->dropConstrainedForeignId('agente_id');
            });
        }
    }
};