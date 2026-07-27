<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\CargoRequest;
use App\Models\Cargo;
use App\Models\Departamento;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

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
        return [
            'nome',
            'cbo',
            'descricao',
        ];
    }

    public function index(Request $request): View
    {
        $query = $this->scopedQuery()
            ->with('departamento:id,nome')
            ->withCount('funcionarios');

        if ($request->filled('search')) {
            $search = trim($request->string('search')->toString());

            $query->where(function (Builder $builder) use ($search): void {
                foreach ($this->searchableFields() as $field) {
                    $builder->orWhere(
                        $field,
                        'like',
                        "%{$search}%"
                    );
                }

                $builder->orWhereHas(
                    'departamento',
                    fn (Builder $departamento) => $departamento
                        ->where('nome', 'like', "%{$search}%")
                );
            });
        }

        $items = $query
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();

        return view('cargos.index', compact('items'));
    }

    public function create(): View
    {
        $this->ensureTenantIsAvailable();

        return view('cargos.create', [
            'departamentos' => $this->departamentos(),
        ]);
    }

    public function edit($id): View
    {
        /** @var Cargo $item */
        $item = $this->findScopedOrFail($id);

        return view('cargos.edit', [
            'item' => $item,
            'departamentos' => $this->departamentos(),
        ]);
    }

    private function departamentos(): Collection
    {
        return Departamento::query()
            ->where('empresa_id', auth()->user()->empresa_id)
            ->where('ativo', true)
            ->orderBy('nome')
            ->pluck('nome', 'id');
    }
}
