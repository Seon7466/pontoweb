<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('funcionarios', function (Blueprint $table) {

            $table->id();

            $table->foreignId('cargo_id')
                ->nullable()
                ->constrained('cargos')
                ->nullOnDelete();

            $table->foreignId('horario_id')
                ->nullable()
                ->constrained('horarios')
                ->nullOnDelete();

            $table->foreignId('empresa_id')
                ->constrained('empresas')
                ->cascadeOnDelete();

            $table->foreignId('departamento_id')
                ->nullable()
                ->constrained('departamentos')
                ->nullOnDelete();






            $table->string('nome');

            $table->string('cpf', 14)->unique();

            $table->string('rg')->nullable();

            $table->string('pis')->nullable();

            $table->string('matricula')->nullable();

            $table->date('nascimento')->nullable();

            $table->date('admissao');

            $table->date('demissao')->nullable();

            $table->string('email')->nullable();

            $table->string('telefone')->nullable();

            $table->string('foto')->nullable();

            $table->boolean('status')->default(true);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('funcionarios');
    }
};
