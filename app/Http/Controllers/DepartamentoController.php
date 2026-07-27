<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\DepartamentoRequest;
use App\Models\Departamento;
use Illuminate\Database\Eloquent\Builder;

class DepartamentoController extends BaseCrudController
{
    protected string $model = Departamento::class;

    protected string $view = 'departamentos';

    protected string $route = 'departamentos';

    protected string $title = 'Departamento';

    protected ?string $requestClass = DepartamentoRequest::class;

    protected bool $tenantScoped = true;

    protected function searchableFields(): array
    {
        return [
            'nome',
            'responsavel',
        ];
    }

    protected function scopedQuery(): Builder
    {
        return parent::scopedQuery()
            ->withCount([
                'cargos',
                'funcionarios',
            ]);
    }
}
