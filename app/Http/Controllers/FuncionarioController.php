<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\FuncionarioRequest;
use App\Models\Cargo;
use App\Models\Departamento;
use App\Models\Escala;
use App\Models\Funcionario;
use App\Models\Horario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FuncionarioController extends BaseCrudController
{
    protected string $model = Funcionario::class;
    protected string $view = 'funcionarios';
    protected string $route = 'funcionarios';
    protected string $title = 'Funcionário';
    protected ?string $requestClass = FuncionarioRequest::class;
    protected bool $tenantScoped = true;

    public function index(Request $request): View
    {
        $query = $this->scopedQuery()->with([
            'departamento:id,nome',
            'cargo:id,nome',
            'horario:id,descricao',
            'escala:id,descricao',
        ]);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('nome', 'like', "%{$search}%")
                    ->orWhere('cpf', 'like', "%{$search}%")
                    ->orWhere('matricula', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhereHas('departamento', fn (Builder $q) => $q->where('nome', 'like', "%{$search}%"))
                    ->orWhereHas('cargo', fn (Builder $q) => $q->where('nome', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->boolean('status'));
        }

        $items = $query
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();

        $empresaId = auth()->user()->empresa_id;
        $base = Funcionario::query()->where('empresa_id', $empresaId);
        $resumo = [
            'total' => (clone $base)->count(),
            'ativos' => (clone $base)->where('status', true)->count(),
            'inativos' => (clone $base)->where('status', false)->count(),
            'sem_jornada' => (clone $base)
                ->whereNull('horario_id')
                ->whereNull('escala_id')
                ->count(),
        ];

        return view('funcionarios.index', compact('items', 'resumo'));
    }

    public function create(): View
    {
        $this->ensureTenantIsAvailable();

        return view('funcionarios.create', $this->formData());
    }

    public function edit($id): View
    {
        /** @var Funcionario $funcionario */
        $funcionario = $this->findScopedOrFail($id);

        return view('funcionarios.edit', array_merge(
            ['funcionario' => $funcionario],
            $this->formData(),
        ));
    }

    /** @return array<string, mixed> */
    private function formData(): array
    {
        $empresaId = auth()->user()->empresa_id;

        return [
            'departamentos' => Departamento::query()
                ->where('empresa_id', $empresaId)
                ->where('ativo', true)
                ->orderBy('nome')
                ->get(['id', 'nome']),
            'cargos' => Cargo::query()
                ->where('empresa_id', $empresaId)
                ->where('ativo', true)
                ->orderBy('nome')
                ->get(['id', 'nome', 'departamento_id']),
            'horarios' => Horario::query()
                ->where('empresa_id', $empresaId)
                ->orderBy('descricao')
                ->get(['id', 'descricao']),
            'escalas' => Escala::query()
                ->where('empresa_id', $empresaId)
                ->where('ativo', true)
                ->orderBy('descricao')
                ->get(['id', 'descricao']),
        ];
    }

    protected function searchableFields(): array
    {
        return ['nome', 'cpf', 'matricula', 'email', 'telefone'];
    }
}
