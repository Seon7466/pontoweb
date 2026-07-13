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
    Schema::create('batida_pontos', function (Blueprint $table) {

        $table->id();

        $table->foreignId('funcionario_id')
            ->constrained('funcionarios')
            ->cascadeOnDelete();

        $table->date('data');

        $table->dateTime('data_hora');

        $table->enum('tipo', [
            'entrada',
            'saida_intervalo',
            'retorno_intervalo',
            'saida'
        ]);

        $table->string('origem')->default('web');
        // web, app, biometria, reconhecimento_facial

        $table->string('ip')->nullable();

        $table->decimal('latitude',10,7)->nullable();

        $table->decimal('longitude',10,7)->nullable();

        $table->text('observacao')->nullable();

        $table->timestamps();

    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('batida_pontos');
    }
};
