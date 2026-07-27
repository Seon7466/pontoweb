<?php

namespace App\Http\Controllers;

use App\Models\BancoHora;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BancoHoraController extends Controller
{
    public function index(Request $request): View
    {
        $empresaId = auth()->user()?->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'O usuário não está vinculado a uma empresa.'
        );

        $registros = BancoHora::query()
            ->where('empresa_id', $empresaId)
            ->with([
                'funcionario:id,nome,matricula',
            ])
            ->selectRaw(
                'funcionario_id, SUM(minutos) AS saldo, MAX(data) AS ultima_data'
            )
            ->groupBy('funcionario_id')
            ->orderByDesc('ultima_data')
            ->paginate(30)
            ->withQueryString();

        return view('banco-horas.index', compact('registros'));
    }
}
