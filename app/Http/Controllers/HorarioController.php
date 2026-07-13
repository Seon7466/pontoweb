<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\HorarioRequest;
use App\Models\Horario;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class HorarioController extends BaseCrudController
{
    protected string $model = Horario::class;
    protected string $view = 'horarios';
    protected string $route = 'horarios';
    protected string $title = 'Horário';
    protected ?string $requestClass = HorarioRequest::class;
    protected bool $tenantScoped = true;

    protected function searchableFields(): array
    {
        return ['descricao'];
    }

    public function index(Request $request)
    {
        $query = $this->scopedQuery()->withCount('funcionarios');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function (Builder $builder) use ($search) {
                foreach ($this->searchableFields() as $field) {
                    $builder->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        $items = $query
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();

        return view('horarios.index', compact('items'));
    }
}
