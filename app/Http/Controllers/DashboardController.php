<?php

namespace App\Http\Controllers;

use App\Models\BatidaPonto;
use App\Models\Departamento;
use App\Models\Equipamento;
use App\Models\Funcionario;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $empresaId = $user?->empresa_id;

        $funcionariosQuery = Funcionario::query()
            ->when($empresaId, fn (Builder $query) => $query->where('empresa_id', $empresaId));

        $departamentosQuery = Departamento::query()
            ->when($empresaId, fn (Builder $query) => $query->where('empresa_id', $empresaId));

        $equipamentosQuery = Equipamento::query()
            ->when($empresaId, fn (Builder $query) => $query->where('empresa_id', $empresaId));

        $batidasQuery = BatidaPonto::query()
            ->when($empresaId, function (Builder $query) use ($empresaId): void {
                $query->where(function (Builder $scope) use ($empresaId): void {
                    $scope->where('empresa_id', $empresaId)
                        ->orWhereHas(
                            'funcionario',
                            fn (Builder $funcionarios) => $funcionarios->where('empresa_id', $empresaId)
                        );
                });
            });

        $totalFuncionarios = (clone $funcionariosQuery)->count();
        $totalDepartamentos = (clone $departamentosQuery)->count();
        $totalEquipamentos = (clone $equipamentosQuery)->count();
        $batidasHoje = (clone $batidasQuery)
            ->whereDate('data_hora', today())
            ->count();

        $equipamentosAtivos = (clone $equipamentosQuery)
            ->where('ativo', true)
            ->count();

        $equipamentosSemComunicacao = (clone $equipamentosQuery)
            ->where('ativo', true)
            ->where(function (Builder $query): void {
                $query->whereNull('ultima_conexao_em')
                    ->orWhere('ultima_conexao_em', '<', now()->subMinutes(10));
            })
            ->count();

        $ultimasBatidas = (clone $batidasQuery)
            ->with(['funcionario:id,nome,matricula', 'equipamento:id,nome'])
            ->latest('data_hora')
            ->limit(8)
            ->get();

        $equipamentos = (clone $equipamentosQuery)
            ->orderByDesc('ultima_conexao_em')
            ->orderBy('nome')
            ->limit(6)
            ->get()
            ->map(function (Equipamento $equipamento): Equipamento {
                $ultimaConexao = $equipamento->ultima_conexao_em;

                $equipamento->setAttribute(
                    'status_dashboard',
                    ! $equipamento->ativo
                        ? 'inactive'
                        : ($ultimaConexao instanceof CarbonInterface && $ultimaConexao->gte(now()->subMinutes(10))
                            ? 'online'
                            : 'offline')
                );

                return $equipamento;
            });

        return view('dashboard', compact(
            'totalFuncionarios',
            'totalDepartamentos',
            'totalEquipamentos',
            'equipamentosAtivos',
            'equipamentosSemComunicacao',
            'batidasHoje',
            'ultimasBatidas',
            'equipamentos'
        ));
    }
}
