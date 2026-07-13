<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hora_extras', function (Blueprint $table) {

            $table->id();

            $table->foreignId('funcionario_id')
                ->constrained('funcionarios')
                ->cascadeOnDelete();

            $table->date('data');

            $table->integer('minutos');

            $table->decimal('percentual', 5, 2)->default(50.00);
            // 50%, 100%, etc.

            $table->boolean('pago')->default(false);

            $table->text('observacao')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hora_extras');
    }
};
