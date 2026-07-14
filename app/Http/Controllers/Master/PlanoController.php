<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\PlanoRequest;
use App\Models\Plano;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PlanoController extends Controller
{
    public function index(Request $request): View
    {
        $query = Plano::query();

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('nome', 'like', "%{$search}%")
                    ->orWhere('descricao', 'like', "%{$search}%")
                    ->orWhere('suporte', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('ativo', $request->string('status')->toString() === 'ativo');
        }

        $planos = $query
            ->orderBy('ordem')
            ->orderBy('valor_mensal')
            ->orderBy('nome')
            ->paginate(12)
            ->withQueryString();

        $indicadores = [
            'total' => Plano::query()->count(),
            'ativos' => Plano::query()->where('ativo', true)->count(),
            'com_api' => Plano::query()->where('api_disponivel', true)->count(),
            'enterprise' => Plano::query()->whereNull('max_funcionarios')->count(),
        ];

        return view('master.planos.index', compact('planos', 'indicadores'));
    }

    public function create(): View
    {
        return view('master.planos.create');
    }

    public function store(PlanoRequest $request): RedirectResponse
    {
        Plano::create($request->validated());

        return redirect()
            ->route('master.planos.index')
            ->with('success', 'Plano cadastrado com sucesso.');
    }

    public function edit(Plano $plano): View
    {
        return view('master.planos.edit', compact('plano'));
    }

    public function update(PlanoRequest $request, Plano $plano): RedirectResponse
    {
        $plano->update($request->validated());

        return redirect()
            ->route('master.planos.index')
            ->with('success', 'Plano atualizado com sucesso.');
    }

    public function destroy(Plano $plano): RedirectResponse
    {
        $plano->delete();

        return redirect()
            ->route('master.planos.index')
            ->with('success', 'Plano excluído com sucesso.');
    }
}
