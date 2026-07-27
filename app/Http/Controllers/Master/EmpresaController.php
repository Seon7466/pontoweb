<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmpresaRequest;
use App\Http\Requests\UpdateEmpresaRequest;
use App\Models\Empresa;
use App\Models\Licenca;
use App\Models\Plano;
use App\Services\Empresa\EmpresaService;

class EmpresaController extends Controller
{
    public function index()
    {
        $empresas = Empresa::query()
            ->with([
                'licenca.plano',
                'usuarios',
                'funcionarios',
                'equipamentos',
            ])
            ->orderBy('razao_social')
            ->paginate(12);

        $indicadores = [
            'total' => Empresa::count(),
            'ativas' => Empresa::where('ativo', true)->count(),
            'inativas' => Empresa::where('ativo', false)->count(),
            'licencas_ativas' => Licenca::whereIn('status', [
                Licenca::STATUS_ATIVA,
                Licenca::STATUS_TESTE,
            ])->count(),
        ];

        return view('empresas.index', compact(
            'empresas',
            'indicadores'
        ));
    }

    public function create()
    {
        $planos = Plano::query()
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view('empresas.create', compact('planos'));
    }

    public function store(
        StoreEmpresaRequest $request,
        EmpresaService $service
    ) {
        $resultado = $service->criar($request->validated());

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa cadastrada com sucesso.')
            ->with('agent_token', $resultado['agent_token']);
    }

    public function edit(Empresa $empresa)
    {
        $empresa->load('licenca');

        $planos = Plano::query()
            ->where('ativo', true)
            ->orderBy('nome')
            ->get();

        return view(
            'empresas.edit',
            compact('empresa', 'planos')
        );
    }

    public function update(
        UpdateEmpresaRequest $request,
        Empresa $empresa,
        EmpresaService $service
    ) {
        $service->atualizar($empresa, $request->validated());

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa atualizada com sucesso.');
    }

    public function destroy(
        Empresa $empresa,
        EmpresaService $service
    ) {
        $service->excluir($empresa);

        return redirect()
            ->route('empresas.index')
            ->with('success', 'Empresa removida com sucesso.');
    }
}
