<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ferias', function (Blueprint $table) {

            $table->id();

            $table->foreignId('funcionario_id')
                ->constrained('funcionarios')
                ->cascadeOnDelete();

            $table->date('periodo_aquisitivo_inicio');
            $table->date('periodo_aquisitivo_fim');

            $table->date('inicio');
            $table->date('fim');

            $table->integer('dias')->default(30);

            $table->boolean('vendidas')->default(false);

            $table->integer('dias_vendidos')->default(0);

            $table->date('data_pagamento')->nullable();

            $table->enum('status',[
                'programada',
                'em_andamento',
                'concluida',
                'cancelada'
            ])->default('programada');

            $table->text('observacao')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ferias');
    }
};
