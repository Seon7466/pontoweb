<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('fechamento_pontos')) {
            return;
        }

        Schema::table('fechamento_pontos', function (Blueprint $table) {
            if (! Schema::hasColumn('fechamento_pontos', 'empresa_id')) {
                $table->foreignId('empresa_id')
                    ->nullable()
                    ->after('id')
                    ->constrained('empresas')
                    ->cascadeOnDelete();
            }

            if (! Schema::hasColumn('fechamento_pontos', 'ano')) {
                $table->unsignedSmallInteger('ano')
                    ->nullable()
                    ->after('empresa_id');
            }

            if (! Schema::hasColumn('fechamento_pontos', 'mes')) {
                $table->unsignedTinyInteger('mes')
                    ->nullable()
                    ->after('ano');
            }

            if (! Schema::hasColumn('fechamento_pontos', 'fechado_em')) {
                $table->timestamp('fechado_em')
                    ->nullable()
                    ->after('mes');
            }

            if (! Schema::hasColumn('fechamento_pontos', 'user_id')) {
                $table->foreignId('user_id')
                    ->nullable()
                    ->after('fechado_em')
                    ->constrained('users')
                    ->nullOnDelete();
            }
        });

        Schema::table('fechamento_pontos', function (Blueprint $table) {
            $table->unique(
                ['empresa_id', 'ano', 'mes'],
                'fechamento_empresa_periodo_unique'
            );
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('fechamento_pontos')) {
            return;
        }

        Schema::table('fechamento_pontos', function (Blueprint $table) {
            $table->dropUnique('fechamento_empresa_periodo_unique');

            if (Schema::hasColumn('fechamento_pontos', 'user_id')) {
                $table->dropConstrainedForeignId('user_id');
            }

            if (Schema::hasColumn('fechamento_pontos', 'empresa_id')) {
                $table->dropConstrainedForeignId('empresa_id');
            }

            $table->dropColumn([
                'ano',
                'mes',
                'fechado_em',
            ]);
        });
    }
};