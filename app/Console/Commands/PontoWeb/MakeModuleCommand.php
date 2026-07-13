<?php

namespace App\Console\Commands\PontoWeb;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MakeModuleCommand extends Command
{
    protected $signature = 'pontoweb:make-module {name}';

    protected $description = 'Cria um módulo CRUD completo do PontoWeb';

    public function handle()
    {
        $name = Str::studly($this->argument('name'));        // Departamento
        $plural = Str::pluralStudly($name);                  // Departamentos
        $model = Str::singular($name);                       // Departamento
        $route = Str::kebab(Str::plural($model));            // departamentos
        $view = Str::snake(Str::plural($model));             // departamentos
        $title = Str::headline($model);                      // Departamento

        $this->info("Criando módulo: {$title}");

        $this->createModel($model);

        $this->createController($model, $view, $route, $title);

        $this->createRequest($model);

        $this->createViews($view, $route, $title);

        $this->createMigration($view);

        $this->newLine();
        $this->info("Módulo {$title} criado com sucesso!");
        $this->warn("Agora adicione a rota em routes/web.php:");
        $this->line("Route::resource('{$route}', \\App\\Http\\Controllers\\{$model}Controller::class)->except(['show']);");

        return self::SUCCESS;
    }

    protected function createModel(string $model): void
    {
        $path = app_path("Models/{$model}.php");

        if (File::exists($path)) {
            $this->warn("Model já existe: {$model}");
            return;
        }

        File::put($path, <<<PHP
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class {$model} extends Model
{
    protected \$fillable = [
        'descricao',
        'ativo',
    ];
}

PHP);

        $this->comment("✔ Model criada");
    }

    protected function createController(string $model, string $view, string $route, string $title): void
    {
        $path = app_path("Http/Controllers/{$model}Controller.php");

        if (File::exists($path)) {
            $this->warn("Controller já existe: {$model}Controller");
            return;
        }

        File::put($path, <<<PHP
<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Models\\{$model};

class {$model}Controller extends BaseCrudController
{
    protected string \$model = {$model}::class;

    protected string \$view = '{$view}';

    protected string \$route = '{$route}';

    protected string \$title = '{$title}';

    protected function searchableFields(): array
    {
        return [
            'descricao',
        ];
    }
}

PHP);

        $this->comment("✔ Controller criado");
    }

    protected function createViews(string $view, string $route, string $title): void
    {
        $dir = resource_path("views/{$view}");

        File::ensureDirectoryExists($dir);

        File::put("{$dir}/index.blade.php", $this->indexView($route, $title));
        File::put("{$dir}/create.blade.php", $this->createView($route, $view, $title));
        File::put("{$dir}/edit.blade.php", $this->editView($route, $view, $title));
        File::put("{$dir}/form.blade.php", $this->formView());

        $this->comment("✔ Views criadas");
    }

    protected function createMigration(string $table): void
    {
        $timestamp = now()->format('Y_m_d_His');
        $path = database_path("migrations/{$timestamp}_create_{$table}_table.php");

        File::put($path, <<<PHP
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('{$table}', function (Blueprint \$table) {
            \$table->id();
            \$table->string('descricao');
            \$table->boolean('ativo')->default(true);
            \$table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('{$table}');
    }
};

PHP);

        $this->comment("✔ Migration criada");
    }

    protected function indexView(string $route, string $title): string
    {
        return <<<BLADE
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {$title}s
        </h2>
    </x-slot>

    <x-ui.alert />

    <div class="mb-4 flex justify-end">
        <a href="{{ route('{$route}.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded">
            Novo {$title}
        </a>
    </div>

    <div class="bg-white shadow rounded p-6">
        <table class="w-full">
            <thead>
                <tr>
                    <th class="border-b p-2 text-left">ID</th>
                    <th class="border-b p-2 text-left">Descrição</th>
                    <th class="border-b p-2 text-left">Status</th>
                    <th class="border-b p-2 text-left">Ações</th>
                </tr>
            </thead>
            <tbody>
                @forelse (\$items as \$item)
                    <tr>
                        <td class="border-b p-2">{{ \$item->id }}</td>
                        <td class="border-b p-2">{{ \$item->descricao }}</td>
                        <td class="border-b p-2">{{ \$item->ativo ? 'Ativo' : 'Inativo' }}</td>
                        <td class="border-b p-2">
                            <a href="{{ route('{$route}.edit', \$item->id) }}" class="text-blue-600">
                                Editar
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="p-4 text-center text-gray-500">
                            Nenhum registro encontrado.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="mt-4">
            {{ \$items->links() }}
        </div>
    </div>
</x-app-layout>
BLADE;
    }

    protected function createView(string $route, string $view, string $title): string
    {
        return <<<BLADE
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Novo {$title}
        </h2>
    </x-slot>

    <x-ui.alert />

    <div class="bg-white shadow rounded p-6">
        <form method="POST" action="{{ route('{$route}.store') }}">
            @csrf

            @include('{$view}.form', ['item' => null])

            <div class="mt-6 flex gap-2">
                <x-button.primary>
                    Salvar
                </x-button.primary>

                <x-button.secondary href="{{ route('{$route}.index') }}">
                    Voltar
                </x-button.secondary>
            </div>
        </form>
    </div>
</x-app-layout>
BLADE;
    }

    protected function editView(string $route, string $view, string $title): string
    {
        return <<<BLADE
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Editar {$title}
        </h2>
    </x-slot>

    <x-ui.alert />

    <div class="bg-white shadow rounded p-6">
        <form method="POST" action="{{ route('{$route}.update', \$item->id) }}">
            @csrf
            @method('PUT')

            @include('{$view}.form', ['item' => \$item])

            <div class="mt-6 flex gap-2">
                <x-button.primary>
                    Atualizar
                </x-button.primary>

                <x-button.secondary href="{{ route('{$route}.index') }}">
                    Voltar
                </x-button.secondary>
            </div>
        </form>
    </div>
</x-app-layout>
BLADE;
    }

    protected function formView(): string
    {
        return <<<BLADE
<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <x-form.input
        label="Descrição"
        name="descricao"
        :value="\$item->descricao ?? ''"
    />
</div>
BLADE;
    }
    protected function createRequest(string $model): void
{
    $dir = app_path('Http/Requests');

    File::ensureDirectoryExists($dir);

    $path = "{$dir}/{$model}Request.php";

    if (File::exists($path)) {
        $this->warn("FormRequest já existe.");
        return;
    }

    File::put($path, <<<PHP
<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class {$model}Request extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'descricao' => 'required|max:255',
        ];
    }
}

PHP);

    $this->comment("✔ FormRequest criado");
}
}
