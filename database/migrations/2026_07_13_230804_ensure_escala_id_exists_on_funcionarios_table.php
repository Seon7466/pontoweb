<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('funcionarios', 'escala_id')) {
            Schema::table('funcionarios', function (Blueprint $table) {
                $table->foreignId('escala_id')
                    ->nullable()
                    ->after('horario_id')
                    ->constrained('escalas')
                    ->nullOnDelete();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('funcionarios', 'escala_id')) {
            Schema::table('funcionarios', function (Blueprint $table) {
                $table->dropConstrainedForeignId('escala_id');
            });
        }
    }
};