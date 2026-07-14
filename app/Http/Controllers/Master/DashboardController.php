<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Empresa;
use App\Models\Equipamento;
use App\Models\Funcionario;
use App\Models\Plano;
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
        ];

        return view('master.dashboard', compact('indicadores'));
    }
}
