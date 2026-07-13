<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\EscalaRequest;
use App\Models\Escala;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EscalaController extends BaseCrudController
{
    protected string $model = Escala::class;
    protected string $view = 'escalas';
    protected string $route = 'escalas';
    protected string $title = 'Escala';
    protected ?string $requestClass = EscalaRequest::class;
    protected bool $tenantScoped = true;

    public function index(Request $request): View
    {
        $query = $this->scopedQuery()->withCount('funcionarios');

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function (Builder $builder) use ($search): void {
                $builder
                    ->where('descricao', 'like', "%{$search}%")
                    ->orWhere('tipo', 'like', "%{$search}%");
            });
        }

        $items = $query
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();

        $empresaId = auth()->user()->empresa_id;
        $resumo = [
            'total' => Escala::query()->where('empresa_id', $empresaId)->count(),
            'ativas' => Escala::query()->where('empresa_id', $empresaId)->where('ativo', true)->count(),
            'inativas' => Escala::query()->where('empresa_id', $empresaId)->where('ativo', false)->count(),
            'funcionarios' => Escala::query()
                ->where('empresa_id', $empresaId)
                ->withCount('funcionarios')
                ->get()
                ->sum('funcionarios_count'),
        ];

        return view('escalas.index', compact('items', 'resumo'));
    }

    protected function searchableFields(): array
    {
        return ['descricao', 'tipo'];
    }
}
