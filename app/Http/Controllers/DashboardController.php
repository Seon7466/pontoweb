<?php

namespace App\Http\Controllers;

use App\Models\BatidaPonto;
use App\Models\Empresa;
use App\Models\Funcionario;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $empresaId = $user?->empresa_id;

        $totalEmpresas = $empresaId
            ? Empresa::query()->whereKey($empresaId)->count()
            : Empresa::query()->count();

        $totalFuncionarios = Funcionario::query()
            ->when($empresaId, fn ($query) => $query->where('empresa_id', $empresaId))
            ->count();

        $batidasHoje = BatidaPonto::query()
            ->when(
                $empresaId,
                fn ($query) => $query->whereHas(
                    'funcionario',
                    fn ($funcionarios) => $funcionarios->where('empresa_id', $empresaId)
                )
            )
            ->whereDate('data_hora', today())
            ->count();

        $totalPendencias = 0;

        return view('dashboard', compact(
            'totalEmpresas',
            'totalFuncionarios',
            'batidasHoje',
            'totalPendencias'
        ));
    }
}
