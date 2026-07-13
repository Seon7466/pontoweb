<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\EmpresaRequest;
use App\Models\Empresa;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class EmpresaController extends BaseCrudController
{
    protected string $model = Empresa::class;
    protected string $view = 'empresas';
    protected string $route = 'empresas';
    protected string $title = 'Empresa';
    protected ?string $requestClass = EmpresaRequest::class;

    protected function searchableFields(): array
    {
        return [
            'razao_social',
            'nome_fantasia',
            'cnpj',
            'email',
            'cidade',
        ];
    }

    protected function scopedQuery(): Builder
    {
        $empresaId = auth()->user()?->empresa_id;

        if (! $empresaId) {
            return Empresa::query()->whereRaw('1 = 0');
        }

        return Empresa::query()->whereKey($empresaId);
    }

    public function create()
    {
        abort_if(auth()->user()?->empresa_id, 403, 'Este usuário já está vinculado a uma empresa.');

        return parent::create();
    }

    public function store(Request $request)
    {
        abort_if(auth()->user()?->empresa_id, 403, 'Este usuário já está vinculado a uma empresa.');

        try {
            DB::transaction(function () use ($request): void {
                $empresa = Empresa::create($this->prepareData($request));

                auth()->user()->update([
                    'empresa_id' => $empresa->id,
                ]);
            });

            return redirect()
                ->route('empresas.index')
                ->with('success', 'Empresa cadastrada e vinculada ao usuário com sucesso.');
        } catch (\Throwable $e) {
            Log::error($e);

            return back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar a empresa.');
        }
    }
}
