<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipamentoRequest;
use App\Http\Requests\ImportarMarcacoesRequest;
use App\Http\Requests\SincronizarControlIdRequest;
use App\Models\Equipamento;
use App\Services\Ponto\ImportadorCsvService;
use App\Services\Relogios\ControlId\ControlIdClassAdapter;
use App\Services\Relogios\ControlId\ControlIdSyncService;
use Illuminate\Http\Request;

class EquipamentoController extends Controller
{
    public function index(Request $request)
    {
        $empresaId = auth()->user()->empresa_id;
        abort_unless($empresaId, 403, 'Vincule uma empresa ao usuário.');

        $search = trim((string) $request->string('search'));

        $baseQuery = Equipamento::query()
            ->where('empresa_id', $empresaId);

        $indicadores = [
            'total' => (clone $baseQuery)->count(),
            'ativos' => (clone $baseQuery)->where('ativo', true)->count(),
            'online' => (clone $baseQuery)
                ->where('ativo', true)
                ->where('ultima_conexao_em', '>=', now()->subMinutes(10))
                ->count(),
            'offline' => (clone $baseQuery)
                ->where('ativo', true)
                ->where(function ($query) {
                    $query->whereNull('ultima_conexao_em')
                        ->orWhere('ultima_conexao_em', '<', now()->subMinutes(10));
                })
                ->count(),
        ];

        $items = (clone $baseQuery)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('nome', 'like', "%{$search}%")
                        ->orWhere('fabricante', 'like', "%{$search}%")
                        ->orWhere('modelo', 'like', "%{$search}%")
                        ->orWhere('numero_serie', 'like', "%{$search}%")
                        ->orWhere('ip', 'like', "%{$search}%")
                        ->orWhere('mac', 'like', "%{$search}%");
                });
            })
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $equipamentosImportacao = (clone $baseQuery)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get(['id', 'nome', 'modelo']);

        return view('equipamentos.index', compact(
            'items',
            'indicadores',
            'equipamentosImportacao',
            'search'
        ));
    }

    public function create()
    {
        abort_unless(auth()->user()->empresa_id, 403, 'Vincule uma empresa ao usuário.');

        return view('equipamentos.create');
    }

    public function store(EquipamentoRequest $request)
    {
        $data = $this->normalizarDados($request);
        Equipamento::create($data + ['empresa_id' => auth()->user()->empresa_id]);

        return redirect()->route('equipamentos.index')->with('success', 'Equipamento cadastrado com sucesso.');
    }

    public function edit(Equipamento $equipamento)
    {
        $this->autorizar($equipamento);

        return view('equipamentos.edit', compact('equipamento'));
    }

    public function update(EquipamentoRequest $request, Equipamento $equipamento)
    {
        $this->autorizar($equipamento);
        $data = $this->normalizarDados($request);

        if (! $request->filled('senha_api')) {
            unset($data['senha_api']);
        }

        $equipamento->update($data);

        return redirect()->route('equipamentos.index')->with('success', 'Equipamento atualizado com sucesso.');
    }

    public function destroy(Equipamento $equipamento)
    {
        $this->autorizar($equipamento);
        $equipamento->delete();

        return redirect()->route('equipamentos.index')->with('success', 'Equipamento excluído com sucesso.');
    }

    public function importar(ImportarMarcacoesRequest $request, ImportadorCsvService $importador)
    {
        $equipamento = Equipamento::query()
            ->where('empresa_id', auth()->user()->empresa_id)
            ->findOrFail($request->integer('equipamento_id'));

        try {
            $resultado = $importador->importar($request->file('arquivo'), $equipamento);
        } catch (\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }

        return back()->with('success', sprintf(
            'Importação concluída: %d lidas, %d importadas, %d duplicadas e %d erros.',
            $resultado['lidas'], $resultado['importadas'], $resultado['duplicadas'], $resultado['erros']
        ));
    }

    public function testarConexao(Equipamento $equipamento, ControlIdClassAdapter $adapter)
    {
        $this->autorizar($equipamento);

        try {
            $adapter->testarConexao($equipamento);

            return back()->with('success', 'Conexão com o REP iDClass realizada com sucesso.');
        } catch (\Throwable $e) {
            $equipamento->forceFill([
                'ultima_falha_em' => now(),
                'ultima_mensagem' => $e->getMessage(),
            ])->save();

            return back()->with('error', 'Falha na conexão: '.$e->getMessage());
        }
    }

    public function sincronizar(
        SincronizarControlIdRequest $request,
        Equipamento $equipamento,
        ControlIdSyncService $service,
    ) {
        $this->autorizar($equipamento);

        try {
            $resultado = $service->sincronizar($equipamento, $request->boolean('processar', true));

            return back()->with('success', sprintf(
                'Sincronização concluída: %d recebidas, %d novas, %d duplicadas, %d processadas e %d erros.',
                $resultado['recebidas'],
                $resultado['novas'],
                $resultado['duplicadas'],
                $resultado['processadas'],
                $resultado['erros']
            ));
        } catch (\Throwable $e) {
            return back()->with('error', 'Falha na sincronização: '.$e->getMessage());
        }
    }

    private function normalizarDados(EquipamentoRequest $request): array
    {
        return $request->validated() + [
            'ativo' => $request->boolean('ativo'),
            'verificar_ssl' => $request->boolean('verificar_ssl'),
            'modo_671' => $request->boolean('modo_671'),
        ];
    }

    private function autorizar(Equipamento $equipamento): void
    {
        abort_unless($equipamento->empresa_id === auth()->user()->empresa_id, 404);
    }
}
