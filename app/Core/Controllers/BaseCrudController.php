<?php

namespace App\Core\Controllers;

use App\Core\Traits\HasPagination;
use App\Core\Traits\HasSearch;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

abstract class BaseCrudController extends Controller
{
    use HasPagination;
    use HasSearch;

    protected string $model;
    protected string $view;
    protected string $route;
    protected string $title = 'Registro';

    /** @var class-string<FormRequest>|null */
    protected ?string $requestClass = null;

    /**
     * Ative nos controllers cujos registros pertencem a uma empresa.
     */
    protected bool $tenantScoped = false;

    protected string $tenantColumn = 'empresa_id';

    public function index(Request $request)
    {
        $query = $this->scopedQuery();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();

            $query->where(function (Builder $q) use ($search) {
                foreach ($this->searchableFields() as $field) {
                    $q->orWhere($field, 'like', "%{$search}%");
                }
            });
        }

        $items = $query
            ->latest()
            ->paginate($this->perPage)
            ->withQueryString();

        return view($this->view . '.index', compact('items'));
    }

    public function create()
    {
        $this->ensureTenantIsAvailable();

        return view($this->view . '.create');
    }

    public function store(Request $request)
    {
        try {
            $data = $this->prepareData($request);

            $this->model::create($data);

            return redirect()
                ->route($this->route . '.index')
                ->with('success', "{$this->title} cadastrado com sucesso.");
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('error', "Erro ao cadastrar {$this->title}.");
        }
    }

    public function edit($id)
    {
        $item = $this->findScopedOrFail($id);

        return view($this->view . '.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        try {
            $item = $this->findScopedOrFail($id);
            $data = $this->prepareData($request);

            $item->update($data);

            return redirect()
                ->route($this->route . '.index')
                ->with('success', "{$this->title} atualizado com sucesso.");
        } catch (ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('error', "Erro ao atualizar {$this->title}.");
        }
    }

    public function destroy($id)
    {
        try {
            $item = $this->findScopedOrFail($id);
            $item->delete();

            return redirect()
                ->route($this->route . '.index')
                ->with('success', "{$this->title} excluído com sucesso.");
        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->with('error', "Erro ao excluir {$this->title}.");
        }
    }

    protected function scopedQuery(): Builder
    {
        /** @var Builder $query */
        $query = $this->model::query();

        if (! $this->tenantScoped) {
            return $query;
        }

        $empresaId = auth()->user()?->empresa_id;

        if (! $empresaId) {
            return $query->whereRaw('1 = 0');
        }

        return $query->where($this->tenantColumn, $empresaId);
    }

    protected function findScopedOrFail($id): Model
    {
        return $this->scopedQuery()->findOrFail($id);
    }

    protected function ensureTenantIsAvailable(): void
    {
        if ($this->tenantScoped && ! auth()->user()?->empresa_id) {
            abort(403, 'Cadastre ou vincule uma empresa antes de acessar este recurso.');
        }
    }

    protected function prepareData(Request $request): array
    {
        if ($this->requestClass !== null) {
            /** @var FormRequest $validatedRequest */
            $validatedRequest = app($this->requestClass);
            $data = $validatedRequest->validated();
        } elseif ($request instanceof FormRequest) {
            $data = $request->validated();
        } else {
            // Compatibilidade temporária para CRUDs ainda sem FormRequest.
            // Novos módulos devem sempre definir $requestClass.
            $data = $request->except(['_token', '_method']);
        }

        if ($this->tenantScoped) {
            $this->ensureTenantIsAvailable();

            // Nunca confie no empresa_id recebido pelo navegador.
            $data[$this->tenantColumn] = auth()->user()->empresa_id;
        }

        return $data;
    }
}
