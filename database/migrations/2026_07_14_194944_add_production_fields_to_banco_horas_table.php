<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('banco_horas', function (Blueprint $table) {
            $table->string('origem', 40)
                ->default('calculo_jornada')
                ->after('motivo');

            $table->string('referencia_tipo', 100)
                ->nullable()
                ->after('origem');

            $table->unsignedBigInteger('referencia_id')
                ->nullable()
                ->after('referencia_tipo');

            $table->text('observacao')
                ->nullable()
                ->after('referencia_id');

            $table->foreignId('registrado_por')
                ->nullable()
                ->after('observacao')
                ->constrained('users')
                ->nullOnDelete();

            $table->index(
                ['referencia_tipo', 'referencia_id'],
                'banco_horas_referencia_index'
            );

            $table->index(
                ['funcionario_id', 'data'],
                'banco_horas_funcionario_data_index'
            );
        });
    }

    public function down(): void
    {
        Schema::table('banco_horas', function (Blueprint $table) {
            $table->dropIndex('banco_horas_referencia_index');
            $table->dropIndex('banco_horas_funcionario_data_index');
            $table->dropConstrainedForeignId('registrado_por');

            $table->dropColumn([
                'origem',
                'referencia_tipo',
                'referencia_id',
                'observacao',
            ]);
        });
    }
};