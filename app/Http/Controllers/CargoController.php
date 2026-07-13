<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\CargoRequest;
use App\Models\Cargo;
use App\Models\Departamento;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class CargoController extends BaseCrudController
{
    protected string $model = Cargo::class;
    protected string $view = 'cargos';
    protected string $route = 'cargos';
    protected string $title = 'Cargo';
    protected ?string $requestClass = CargoRequest::class;
    protected bool $tenantScoped = true;

    protected function searchableFields(): array
    {
        return ['nome', 'cbo', 'descricao'];
    }

    public function index(Request $request)
    {
        $query = $this->scopedQuery()
            ->with('departamento:id,nome')
            ->withCount('funcionarios');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function (Builder $builder) use ($search) {
                foreach ($this->searchableFields() as $field) {
                    $builder->orWhere($field, 'like', "%{$search}%");
                }

                $builder->orWhereHas('departamento', function (Builder $departamentoQuery) use ($search) {
                    $departamentoQuery->where('nome', 'like', "%{$search}%");
                });
            });
        }

        $items = $query
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();

        return view('cargos.index', compact('items'));
    }

    public function create()
    {
        $this->ensureTenantIsAvailable();
        $departamentos = $this->departamentos();

        return view('cargos.create', compact('departamentos'));
    }

    public function edit($id)
    {
        $item = $this->findScopedOrFail($id);
        $departamentos = $this->departamentos();

        return view('cargos.edit', compact('item', 'departamentos'));
    }

    private function departamentos()
    {
        return Departamento::query()
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('ativo', true)
            ->orderBy('nome')
            ->pluck('nome', 'id');
    }
}
