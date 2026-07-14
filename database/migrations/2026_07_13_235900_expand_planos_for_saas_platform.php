<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('planos')) {
            Schema::create('planos', function (Blueprint $table) {
                $table->id();
                $table->string('nome')->unique();
                $table->text('descricao')->nullable();
                $table->unsignedInteger('max_funcionarios')->nullable();
                $table->unsignedInteger('max_relogios')->nullable();
                $table->decimal('valor_mensal', 10, 2)->nullable();
                $table->unsignedInteger('armazenamento_gb')->nullable();
                $table->string('suporte', 50)->default('E-mail');
                $table->boolean('api_disponivel')->default(false);
                $table->boolean('app_mobile')->default(false);
                $table->boolean('bi_disponivel')->default(false);
                $table->boolean('geolocalizacao')->default(false);
                $table->boolean('integracoes')->default(false);
                $table->boolean('backup_automatico')->default(true);
                $table->boolean('marketplace')->default(false);
                $table->json('recursos')->nullable();
                $table->unsignedSmallInteger('ordem')->default(0);
                $table->boolean('ativo')->default(true)->index();
                $table->timestamps();
            });

            return;
        }

        Schema::table('planos', function (Blueprint $table) {
            if (! Schema::hasColumn('planos', 'armazenamento_gb')) {
                $table->unsignedInteger('armazenamento_gb')->nullable()->after('valor_mensal');
            }
            if (! Schema::hasColumn('planos', 'suporte')) {
                $table->string('suporte', 50)->default('E-mail')->after('armazenamento_gb');
            }
            if (! Schema::hasColumn('planos', 'api_disponivel')) {
                $table->boolean('api_disponivel')->default(false)->after('suporte');
            }
            if (! Schema::hasColumn('planos', 'app_mobile')) {
                $table->boolean('app_mobile')->default(false)->after('api_disponivel');
            }
            if (! Schema::hasColumn('planos', 'bi_disponivel')) {
                $table->boolean('bi_disponivel')->default(false)->after('app_mobile');
            }
            if (! Schema::hasColumn('planos', 'geolocalizacao')) {
                $table->boolean('geolocalizacao')->default(false)->after('bi_disponivel');
            }
            if (! Schema::hasColumn('planos', 'integracoes')) {
                $table->boolean('integracoes')->default(false)->after('geolocalizacao');
            }
            if (! Schema::hasColumn('planos', 'backup_automatico')) {
                $table->boolean('backup_automatico')->default(true)->after('integracoes');
            }
            if (! Schema::hasColumn('planos', 'marketplace')) {
                $table->boolean('marketplace')->default(false)->after('backup_automatico');
            }
            if (! Schema::hasColumn('planos', 'recursos')) {
                $table->json('recursos')->nullable()->after('marketplace');
            }
            if (! Schema::hasColumn('planos', 'ordem')) {
                $table->unsignedSmallInteger('ordem')->default(0)->after('recursos');
            }
        });

        Schema::table('planos', function (Blueprint $table) {
            $table->unsignedInteger('max_funcionarios')->nullable()->change();
            $table->unsignedInteger('max_relogios')->nullable()->change();
            $table->decimal('valor_mensal', 10, 2)->nullable()->change();
        });
    }

    public function down(): void
    {
        if (! Schema::hasTable('planos')) {
            return;
        }

        $columns = [
            'armazenamento_gb', 'suporte', 'api_disponivel', 'app_mobile',
            'bi_disponivel', 'geolocalizacao', 'integracoes',
            'backup_automatico', 'marketplace', 'recursos', 'ordem',
        ];

        Schema::table('planos', function (Blueprint $table) use ($columns) {
            foreach ($columns as $column) {
                if (Schema::hasColumn('planos', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
