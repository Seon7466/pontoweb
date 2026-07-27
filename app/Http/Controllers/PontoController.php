<?php

namespace App\Http\Controllers;

use App\Models\BatidaPonto;
use App\Models\Funcionario;
use App\Models\MarcacaoRelogio;
use App\Services\Ponto\CalculoJornadaService;
use App\Services\Ponto\CargaHorariaService;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PontoController extends Controller
{
    /**
     * Exibe a página principal do módulo de ponto.
     */
    public function index(Request $request): View
    {
        $empresaId = auth()->user()->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'Vincule uma empresa ao usuário.'
        );

        $data = $request->date('data')?->format('Y-m-d')
            ?? now()->toDateString();

        $funcionarioId = $request->integer('funcionario_id') ?: null;

        $origem = trim(
            $request->string('origem')->toString()
        ) ?: null;

        $funcionarios = Funcionario::query()
            ->where('empresa_id', $empresaId)
            ->where('status', true)
            ->orderBy('nome')
            ->get([
                'id',
                'nome',
                'matricula',
                'codigo_relogio',
            ]);

        $baseBatidas = BatidaPonto::query()
            ->where('empresa_id', $empresaId)
            ->whereDate('data', $data)
            ->when(
                $funcionarioId,
                fn($query) => $query->where(
                    'funcionario_id',
                    $funcionarioId
                )
            )
            ->when(
                $origem,
                fn($query) => $query->where(
                    'origem',
                    $origem
                )
            );

        $indicadores = [
            'batidas' => (clone $baseBatidas)->count(),

            'funcionarios_com_batida' => (clone $baseBatidas)
                ->distinct('funcionario_id')
                ->count('funcionario_id'),

            'manuais' => (clone $baseBatidas)
                ->where('manual', true)
                ->count(),

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
            ->latest('data_hora')
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

    /**
     * Exibe o espelho de ponto em HTML.
     */
    public function espelho(
        Request $request,
        CargaHorariaService $cargaHorariaService,
        CalculoJornadaService $calculoJornadaService,
    ): View {
        $dados = $this->montarEspelho(
            $request,
            $cargaHorariaService,
            $calculoJornadaService
        );

        return view('ponto.espelho', $dados);
    }

    /**
     * Gera o espelho de ponto em PDF.
     */
    public function espelhoPdf(
        Request $request,
        CargaHorariaService $cargaHorariaService,
        CalculoJornadaService $calculoJornadaService,
    ): Response {
        $dados = $this->montarEspelho(
            $request,
            $cargaHorariaService,
            $calculoJornadaService
        );

        $funcionario = $dados['funcionario'];
        $inicio = $dados['inicio'];
        $fim = $dados['fim'];

        $nomeFuncionario = str($funcionario->nome)
            ->slug()
            ->toString();

        $nomeArquivo = sprintf(
            'espelho-ponto-%s-%s-a-%s.pdf',
            $nomeFuncionario,
            $inicio->format('Y-m-d'),
            $fim->format('Y-m-d')
        );

        return Pdf::loadView(
            'ponto.espelho-pdf',
            $dados
        )
            ->setPaper('a4', 'portrait')
            ->download($nomeArquivo);
    }

    /**
     * Registra uma batida manual de contingência.
     */
    public function registrarContingencia(
        Request $request
    ): RedirectResponse {
        $empresaId = auth()->user()->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'Vincule uma empresa ao usuário.'
        );

        $dados = $request->validate([
            'funcionario_id' => [
                'required',
                'integer',
                Rule::exists('funcionarios', 'id')
                    ->where(
                        fn($query) => $query->where(
                            'empresa_id',
                            $empresaId
                        )
                    ),
            ],

            'observacao' => [
                'required',
                'string',
                'min:5',
                'max:500',
            ],
        ]);

        DB::transaction(
            function () use (
                $dados,
                $empresaId,
                $request
            ): void {
                $agora = now();

                $quantidade = BatidaPonto::query()
                    ->where('empresa_id', $empresaId)
                    ->where(
                        'funcionario_id',
                        $dados['funcionario_id']
                    )
                    ->whereDate(
                        'data',
                        $agora->toDateString()
                    )
                    ->count();

                if ($quantidade >= 4) {
                    abort(
                        422,
                        'O funcionário já possui quatro batidas hoje. '
                            . 'Faça um ajuste administrativo posteriormente.'
                    );
                }

                BatidaPonto::create([
                    'empresa_id' => $empresaId,

                    'funcionario_id' =>
                    $dados['funcionario_id'],

                    'data' => $agora->toDateString(),

                    'data_hora' => $agora,

                    'tipo' => [
                        'entrada',
                        'saida_intervalo',
                        'retorno_intervalo',
                        'saida',
                    ][$quantidade],

                    'origem' => 'web_contingencia',

                    'ip' => $request->ip(),

                    'user_agent' =>
                    $request->userAgent(),

                    'observacao' =>
                    $dados['observacao'],

                    'manual' => true,

                    'registrado_por' => auth()->id(),
                ]);
            }
        );

        return back()->with(
            'success',
            'Batida de contingência registrada com sucesso.'
        );
    }

    /**
     * Monta os dados utilizados pela tela e pelo PDF.
     */
    private function montarEspelho(
        Request $request,
        CargaHorariaService $cargaHorariaService,
        CalculoJornadaService $calculoJornadaService,
    ): array {
        $dados = $this->validarEspelho($request);

        $inicio = Carbon::parse(
            $dados['inicio']
        )->startOfDay();

        $fim = Carbon::parse(
            $dados['fim']
        )->startOfDay();

        $funcionario = $this->buscarFuncionario(
            $dados['empresa_id'],
            $dados['funcionario_id']
        );

        $batidasPorData = $this->buscarBatidas(
            $dados['empresa_id'],
            $funcionario->id,
            $inicio,
            $fim
        );

        $resultado = $this->processarDias(
            $funcionario,
            $batidasPorData,
            $inicio,
            $fim,
            $cargaHorariaService,
            $calculoJornadaService
        );

        return [
            'funcionario' => $funcionario,
            'inicio' => $inicio,
            'fim' => $fim,
            'dias' => $resultado['dias'],
            'totais' => $resultado['totais'],
        ];
    }

    /**
     * Valida os filtros do espelho.
     */
    private function validarEspelho(
        Request $request
    ): array {
        $empresaId = auth()->user()->empresa_id;

        abort_unless(
            $empresaId,
            403,
            'Vincule uma empresa ao usuário.'
        );

        $dados = $request->validate([
            'funcionario_id' => [
                'required',
                'integer',

                Rule::exists('funcionarios', 'id')
                    ->where(
                        fn($query) => $query->where(
                            'empresa_id',
                            $empresaId
                        )
                    ),
            ],

            'inicio' => [
                'required',
                'date',
            ],

            'fim' => [
                'required',
                'date',
                'after_or_equal:inicio',
            ],
        ]);

        $dados['empresa_id'] = $empresaId;

        return $dados;
    }

    /**
     * Busca o funcionário e os dados necessários
     * para o cabeçalho do espelho.
     */
    private function buscarFuncionario(
        int $empresaId,
        int $funcionarioId
    ): Funcionario {
        return Funcionario::query()
            ->with([
                'empresa',
                'departamento',
                'cargo',
                'horario',
            ])
            ->where('empresa_id', $empresaId)
            ->findOrFail($funcionarioId);
    }

    /**
     * Busca e agrupa as batidas por data.
     */
    private function buscarBatidas(
        int $empresaId,
        int $funcionarioId,
        Carbon $inicio,
        Carbon $fim,
    ): Collection {
        return BatidaPonto::query()
            ->where('empresa_id', $empresaId)
            ->where(
                'funcionario_id',
                $funcionarioId
            )
            ->whereBetween('data', [
                $inicio->toDateString(),
                $fim->toDateString(),
            ])
            ->orderBy('data_hora')
            ->get()
            ->groupBy(
                fn(BatidaPonto $batida) =>
                $batida->data->toDateString()
            );
    }

    /**
     * Processa cada dia do período.
     */
    private function processarDias(
        Funcionario $funcionario,
        Collection $batidasPorData,
        Carbon $inicio,
        Carbon $fim,
        CargaHorariaService $cargaHorariaService,
        CalculoJornadaService $calculoJornadaService,
    ): array {
        $dias = [];

        $totais = [
            'previstos' => 0,
            'trabalhados' => 0,
            'creditos' => 0,
            'debitos' => 0,
            'saldo' => 0,
        ];

        foreach (
            CarbonPeriod::create($inicio, $fim) as $data
        ) {
            $dataString = $data->toDateString();

            $batidas = $batidasPorData->get(
                $dataString,
                collect()
            );

            $cargaHorariaMinutos = 0;

            if ($funcionario->horario) {
                $horario = $funcionario->horario;

                $cargaHorariaMinutos =
                    $cargaHorariaService->calcular(
                        data: $dataString,
                        entrada: $horario->entrada,
                        saida: $horario->saida,
                        inicioIntervalo: $horario->inicio_intervalo,
                        fimIntervalo: $horario->fim_intervalo,
                    );
            }

            $horariosBatidas = $batidas
                ->map(
                    fn(BatidaPonto $batida) =>
                    $batida->data_hora->format(
                        'Y-m-d H:i:s'
                    )
                )
                ->all();

            $resultado =
                $calculoJornadaService->calcular(
                    $horariosBatidas,
                    $cargaHorariaMinutos
                );

            $dias[] = [
                'data' => $data->copy(),
                'batidas' => $batidas,

                'previstos' =>
                $cargaHorariaMinutos,

                'trabalhados' =>
                $resultado['trabalhado'],

                'creditos' =>
                $resultado['extra'],

                'debitos' =>
                $resultado['atraso'],

                'saldo' =>
                $resultado['saldo'],
            ];

            $totais['previstos'] +=
                $cargaHorariaMinutos;

            $totais['trabalhados'] +=
                $resultado['trabalhado'];

            $totais['creditos'] +=
                $resultado['extra'];

            $totais['debitos'] +=
                $resultado['atraso'];

            $totais['saldo'] +=
                $resultado['saldo'];
        }

        return [
            'dias' => $dias,
            'totais' => $totais,
        ];
    }
}
