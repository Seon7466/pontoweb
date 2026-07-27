<?php

namespace App\Http\Controllers;

use App\Http\Requests\EquipamentoRequest;
use App\Http\Requests\ImportarMarcacoesRequest;
use App\Http\Requests\SincronizarControlIdRequest;
use App\Models\Equipamento;
use App\Services\Ponto\ImportadorCsvService;
use App\Services\Relogios\ControlId\ControlIdClassAdapter;
use App\Services\Relogios\ControlId\ControlIdSyncService;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Throwable;

class EquipamentoController extends Controller
{
    public function index(Request $request): View
    {
        $empresaId = $this->empresaId();
        $search = trim((string) $request->string('search'));
        $limiteOnline = now()->subMinutes(10);

        $baseQuery = Equipamento::query()
            ->where('empresa_id', $empresaId);

        $indicadores = [
            'total' => (clone $baseQuery)->count(),

            'ativos' => (clone $baseQuery)
                ->where('ativo', true)
                ->count(),

            'online' => (clone $baseQuery)
                ->where('ativo', true)
                ->where('ultima_conexao_em', '>=', $limiteOnline)
                ->count(),

            'offline' => (clone $baseQuery)
                ->where('ativo', true)
                ->where(function (Builder $query) use ($limiteOnline): void {
                    $query
                        ->whereNull('ultima_conexao_em')
                        ->orWhere('ultima_conexao_em', '<', $limiteOnline);
                })
                ->count(),
        ];

        $items = (clone $baseQuery)
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $subQuery) use ($search): void {
                            $subQuery
                                ->where('nome', 'like', "%{$search}%")
                                ->orWhere('fabricante', 'like', "%{$search}%")
                                ->orWhere('modelo', 'like', "%{$search}%")
                                ->orWhere('numero_serie', 'like', "%{$search}%")
                                ->orWhere('ip', 'like', "%{$search}%")
                                ->orWhere('mac', 'like', "%{$search}%");
                        }
                    );
                }
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $equipamentosImportacao = (clone $baseQuery)
            ->where('ativo', true)
            ->orderBy('nome')
            ->get([
                'id',
                'nome',
                'modelo',
            ]);

        return view('equipamentos.index', compact(
            'items',
            'indicadores',
            'equipamentosImportacao',
            'search'
        ));
    }

    public function create(): View
    {
        $this->empresaId();

        return view('equipamentos.create');
    }

    public function store(
        EquipamentoRequest $request
    ): RedirectResponse {
        Equipamento::create([
            ...$this->normalizarDados($request),
            'empresa_id' => $this->empresaId(),
        ]);

        return redirect()
            ->route('equipamentos.index')
            ->with(
                'success',
                'Equipamento cadastrado com sucesso.'
            );
    }

    public function edit(
        Equipamento $equipamento
    ): View {
        $this->autorizar($equipamento);

        return view(
            'equipamentos.edit',
            compact('equipamento')
        );
    }

    public function update(
        EquipamentoRequest $request,
        Equipamento $equipamento
    ): RedirectResponse {
        $this->autorizar($equipamento);

        $dados = $this->normalizarDados($request);

        if (! $request->filled('senha_api')) {
            unset($dados['senha_api']);
        }

        $equipamento->update($dados);

        return redirect()
            ->route('equipamentos.index')
            ->with(
                'success',
                'Equipamento atualizado com sucesso.'
            );
    }

    public function destroy(
        Equipamento $equipamento
    ): RedirectResponse {
        $this->autorizar($equipamento);

        $equipamento->delete();

        return redirect()
            ->route('equipamentos.index')
            ->with(
                'success',
                'Equipamento excluído com sucesso.'
            );
    }

    public function importar(
        ImportarMarcacoesRequest $request,
        ImportadorCsvService $importador
    ): RedirectResponse {
        $equipamento = Equipamento::query()
            ->where('empresa_id', $this->empresaId())
            ->findOrFail(
                $request->integer('equipamento_id')
            );

        try {
            $resultado = $importador->importar(
                $request->file('arquivo'),
                $equipamento
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Não foi possível importar as marcações.'
            );
        }

        return back()->with(
            'success',
            sprintf(
                'Importação concluída: %d lidas, %d importadas, %d duplicadas e %d erros.',
                $resultado['lidas'],
                $resultado['importadas'],
                $resultado['duplicadas'],
                $resultado['erros']
            )
        );
    }

    public function testarConexao(
        Equipamento $equipamento,
        ControlIdClassAdapter $adapter
    ): RedirectResponse {
        $this->autorizar($equipamento);

        try {
            $adapter->testarConexao($equipamento);
        } catch (Throwable $exception) {
            report($exception);

            $equipamento->forceFill([
                'ultima_falha_em' => now(),
                'ultima_mensagem' => $exception->getMessage(),
            ])->save();

            return back()->with(
                'error',
                'Não foi possível estabelecer conexão com o equipamento.'
            );
        }

        return back()->with(
            'success',
            'Conexão com o REP iDClass realizada com sucesso.'
        );
    }

    public function sincronizar(
        SincronizarControlIdRequest $request,
        Equipamento $equipamento,
        ControlIdSyncService $service
    ): RedirectResponse {
        $this->autorizar($equipamento);

        try {
            $resultado = $service->sincronizar(
                $equipamento,
                $request->boolean('processar', true)
            );
        } catch (Throwable $exception) {
            report($exception);

            return back()->with(
                'error',
                'Não foi possível sincronizar o equipamento.'
            );
        }

        return back()->with(
            'success',
            sprintf(
                'Sincronização concluída: %d recebidas, %d novas, %d duplicadas, %d processadas e %d erros.',
                $resultado['recebidas'],
                $resultado['novas'],
                $resultado['duplicadas'],
                $resultado['processadas'],
                $resultado['erros']
            )
        );
    }

    private function normalizarDados(
        EquipamentoRequest $request
    ): array {
        return array_replace(
            $request->validated(),
            [
                'ativo' => $request->boolean('ativo'),
                'verificar_ssl' => $request->boolean('verificar_ssl'),
                'modo_671' => $request->boolean('modo_671'),
            ]
        );
    }

    private function autorizar(
        Equipamento $equipamento
    ): void {
        abort_unless(
            (int) $equipamento->empresa_id === $this->empresaId(),
            404
        );
    }

    private function empresaId(): int
    {
        $empresaId = auth()->user()?->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'Vincule uma empresa ao usuário.'
        );

        return (int) $empresaId;
    }
}
