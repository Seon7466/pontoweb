<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Equipamento;
use App\Models\Funcionario;
use App\Models\Plano;
use App\Models\Licenca;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $indicadores = [
            'empresas' => Empresa::query()->count(),
            'funcionarios' => Funcionario::query()->count(),
            'equipamentos' => Equipamento::query()->count(),
            'usuarios' => User::query()->count(),
            'planos' => Plano::query()->count(),
            'planos_ativos' => Plano::query()->where('ativo', true)->count(),
            'licencas' => Licenca::query()->count(),
            'licencas_ativas' => Licenca::query()->whereIn('status', ['ativa', 'teste'])->count(),
            'licencas_vencendo' => Licenca::query()->whereDate('termina_em', '>=', today())->whereDate('termina_em', '<=', today()->addDays(15))->count(),
        ];

        return view('master.dashboard', compact('indicadores'));
    }
}
