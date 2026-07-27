<?php

namespace App\Http\Controllers;

use App\Models\FechamentoPonto;
use App\Services\Ponto\FechamentoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class FechamentoPontoController extends Controller
{
    public function index(): View
    {
        $empresaId = auth()->user()?->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'O usuário não está vinculado a uma empresa.'
        );

        $fechamentos = FechamentoPonto::query()
            ->where('empresa_id', $empresaId)
            ->orderByDesc('ano')
            ->orderByDesc('mes')
            ->paginate(12);

        return view('fechamentos.index', compact('fechamentos'));
    }

    public function store(
        Request $request,
        FechamentoService $service
    ): RedirectResponse {
        $empresaId = auth()->user()?->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'O usuário não está vinculado a uma empresa.'
        );

        $dados = $request->validate([
            'ano' => [
                'required',
                'integer',
                'between:2020,2100',
            ],
            'mes' => [
                'required',
                'integer',
                'between:1,12',
            ],
        ]);

        $service->fechar(
            $empresaId,
            (int) $dados['ano'],
            (int) $dados['mes']
        );

        return back()->with(
            'success',
            'Período fechado com sucesso.'
        );
    }
}
