<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('horarios', function (Blueprint $table) {
            $table->id();

            $table->foreignId('empresa_id')
                ->constrained('empresas')
                ->onDelete('cascade');

            $table->string('descricao');

            $table->time('entrada');
            $table->time('saida');

            $table->time('inicio_intervalo')->nullable();
            $table->time('fim_intervalo')->nullable();

            $table->integer('tolerancia_entrada')->default(0);
            $table->integer('tolerancia_saida')->default(0);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('horarios');
    }
};
