<?php

namespace App\Http\Controllers;

use App\Models\BatidaPonto;
use App\Models\Funcionario;
use App\Models\MarcacaoRelogio;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PontoController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        abort_unless($empresaId, 403, 'Vincule uma empresa ao usuário.');

        $data = $request->date('data')?->format('Y-m-d') ?? now()->toDateString();
        $funcionarioId = $request->integer('funcionario_id') ?: null;
        $origem = $request->string('origem')->toString() ?: null;

        $funcionarios = Funcionario::query()
            ->where('empresa_id', $empresaId)
            ->where('status', true)
            ->orderBy('nome')
            ->get(['id', 'nome', 'matricula', 'codigo_relogio']);

        $baseBatidas = BatidaPonto::query()
            ->where('empresa_id', $empresaId)
            ->whereDate('data', $data)
            ->when($funcionarioId, fn ($query) => $query->where('funcionario_id', $funcionarioId))
            ->when($origem, fn ($query) => $query->where('origem', $origem));

        $indicadores = [
            'batidas' => (clone $baseBatidas)->count(),
            'funcionarios_com_batida' => (clone $baseBatidas)->distinct('funcionario_id')->count('funcionario_id'),
            'manuais' => (clone $baseBatidas)->where('manual', true)->count(),
            'pendentes' => MarcacaoRelogio::query()
                ->where('empresa_id', $empresaId)
                ->where('status', 'erro')
                ->count(),
        ];

        $batidas = (clone $baseBatidas)
            ->with([
                'funcionario:id,nome,matricula',
                'equipamento:id,nome,modelo',
                'usuarioRegistro:id,name',
            ])
            ->orderByDesc('data_hora')
            ->paginate(30)
            ->withQueryString();

        $origens = BatidaPonto::query()
            ->where('empresa_id', $empresaId)
            ->whereNotNull('origem')
            ->distinct()
            ->orderBy('origem')
            ->pluck('origem');

        return view('ponto.index', compact(
            'batidas',
            'funcionarios',
            'data',
            'funcionarioId',
            'origem',
            'origens',
            'indicadores'
        ));
    }

    public function registrarContingencia(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        abort_unless($empresaId, 403, 'Vincule uma empresa ao usuário.');

        $dados = $request->validate([
            'funcionario_id' => [
                'required',
                'integer',
                Rule::exists('funcionarios', 'id')->where(fn ($query) => $query->where('empresa_id', $empresaId)),
            ],
            'observacao' => ['required', 'string', 'min:5', 'max:500'],
        ]);

        DB::transaction(function () use ($dados, $empresaId, $request) {
            $agora = now();
            $quantidade = BatidaPonto::query()
                ->where('empresa_id', $empresaId)
                ->where('funcionario_id', $dados['funcionario_id'])
                ->whereDate('data', $agora->toDateString())
                ->count();

            if ($quantidade >= 4) {
                abort(422, 'O funcionário já possui quatro batidas hoje. Faça um ajuste administrativo posteriormente.');
            }

            $tipos = ['entrada', 'saida_intervalo', 'retorno_intervalo', 'saida'];

            BatidaPonto::create([
                'empresa_id' => $empresaId,
                'funcionario_id' => $dados['funcionario_id'],
                'data' => $agora->toDateString(),
                'data_hora' => $agora,
                'tipo' => $tipos[$quantidade],
                'origem' => 'web_contingencia',
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'observacao' => $dados['observacao'],
                'manual' => true,
                'registrado_por' => auth()->id(),
            ]);
        });

        return back()->with('success', 'Batida de contingência registrada com sucesso.');
    }
}
