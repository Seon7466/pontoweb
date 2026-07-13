<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('escalas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('empresa_id')
                ->constrained('empresas')
                ->cascadeOnDelete();

            $table->string('descricao');

            $table->string('tipo')->default('5x2');
            // exemplos: 5x2, 6x1, 12x36, Personalizada

            $table->boolean('domingo')->default(false);
            $table->boolean('segunda')->default(true);
            $table->boolean('terca')->default(true);
            $table->boolean('quarta')->default(true);
            $table->boolean('quinta')->default(true);
            $table->boolean('sexta')->default(true);
            $table->boolean('sabado')->default(false);

            $table->boolean('ativo')->default(true);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('escalas');
    }
};
