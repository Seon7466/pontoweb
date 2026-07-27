<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fechamento_pontos', function (Blueprint $table) {

            $table->id();

            $table->foreignId('empresa_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->unsignedSmallInteger('ano');

            $table->unsignedTinyInteger('mes');

            $table->timestamp('fechado_em');

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->unique(
                ['empresa_id','ano','mes'],
                'fechamento_empresa_periodo_unique'
            );

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fechamento_pontos');
    }
};