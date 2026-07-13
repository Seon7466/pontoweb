<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('banco_horas', function (Blueprint $table) {

            $table->id();

            $table->foreignId('funcionario_id')
                ->constrained('funcionarios')
                ->cascadeOnDelete();

            $table->date('data');

            $table->integer('minutos');

            $table->enum('tipo', [
                'credito',
                'debito'
            ]);

            $table->string('motivo')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('banco_horas');
    }
};
