<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\EscalaRequest;
use App\Models\Escala;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class EscalaController extends BaseCrudController
{
    protected string $model = Escala::class;

    protected string $view = 'escalas';

    protected string $route = 'escalas';

    protected string $title = 'Escala';

    protected ?string $requestClass = EscalaRequest::class;

    protected bool $tenantScoped = true;

    protected function searchableFields(): array
    {
        return [
            'descricao',
            'tipo',
        ];
    }

    public function index(Request $request): View
    {
        $query = $this->scopedQuery()
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
            });
        }

        $items = $query
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();

        $resumoQuery = $this->scopedQuery();

        $resumo = [
            'total' => (clone $resumoQuery)->count(),

            'ativas' => (clone $resumoQuery)
                ->where('ativo', true)
                ->count(),

            'inativas' => (clone $resumoQuery)
                ->where('ativo', false)
                ->count(),

            'funcionarios' => (clone $resumoQuery)
                ->withCount('funcionarios')
                ->get()
                ->sum('funcionarios_count'),
        ];

        return view('escalas.index', compact('items', 'resumo'));
    }
}
