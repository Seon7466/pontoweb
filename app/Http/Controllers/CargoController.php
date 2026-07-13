<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\CargoRequest;
use App\Models\Cargo;
use App\Models\Departamento;

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
