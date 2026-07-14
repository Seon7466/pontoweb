<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Http\Requests\Master\LicencaRequest;
use App\Models\Empresa;
use App\Models\Licenca;
use App\Models\Plano;
use App\Services\Licencas\LicencaTokenService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LicencaController extends Controller
{
    public function index(Request $request): View
    {
        $query = Licenca::query()->with(['empresa', 'plano']);

        if ($request->filled('search')) {
            $search = $request->string('search')->toString();
            $query->where(function ($q) use ($search) {
                $q->where('codigo', 'like', "%{$search}%")
                    ->orWhereHas('empresa', fn ($empresa) => $empresa
                        ->where('razao_social', 'like', "%{$search}%")
                        ->orWhere('nome_fantasia', 'like', "%{$search}%")
                        ->orWhere('cnpj', 'like', "%{$search}%"));
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->string('status')->toString());
        }

        if ($request->filled('plano_id')) {
            $query->where('plano_id', $request->integer('plano_id'));
        }

        $licencas = $query->latest()->paginate(15)->withQueryString();
        $planos = Plano::query()->orderBy('ordem')->orderBy('nome')->get();

        $indicadores = [
            'total' => Licenca::query()->count(),
            'ativas' => Licenca::query()->whereIn('status', [Licenca::STATUS_ATIVA, Licenca::STATUS_TESTE])
                ->where(fn ($q) => $q->whereNull('termina_em')->orWhereDate('termina_em', '>=', today()))->count(),
            'vencendo' => Licenca::query()->whereDate('termina_em', '>=', today())
                ->whereDate('termina_em', '<=', today()->addDays(15))->count(),
            'bloqueadas' => Licenca::query()->whereIn('status', [Licenca::STATUS_SUSPENSA, Licenca::STATUS_CANCELADA, Licenca::STATUS_VENCIDA])->count(),
        ];

        return view('master.licencas.index', compact('licencas', 'planos', 'indicadores'));
    }

    public function create(): View
    {
        return view('master.licencas.create', [
            'empresas' => Empresa::query()->whereDoesntHave('licenca')->orderBy('nome_fantasia')->get(),
            'planos' => Plano::query()->where('ativo', true)->orderBy('ordem')->orderBy('nome')->get(),
        ]);
    }

    public function store(LicencaRequest $request, LicencaTokenService $tokenService): RedirectResponse
    {
        [$licenca, $token] = DB::transaction(function () use ($request, $tokenService) {
            $data = $request->validated();
            $data['codigo'] = $this->novoCodigo();
            $this->aplicarDatasDeStatus($data);

            $licenca = Licenca::create($data);
            $token = $tokenService->gerar($licenca);
            $this->historico($licenca, 'criada', 'Licença criada e token inicial gerado.', $data);

            return [$licenca, $token];
        });

        return redirect()->route('master.licencas.edit', $licenca)
            ->with('success', 'Licença cadastrada com sucesso.')
            ->with('agent_token', $token);
    }

    public function edit(Licenca $licenca): View
    {
        $licenca->load(['empresa', 'plano', 'historicos.usuario']);

        return view('master.licencas.edit', [
            'licenca' => $licenca,
            'empresas' => Empresa::query()->where(fn ($q) => $q->whereDoesntHave('licenca')->orWhereKey($licenca->empresa_id))->orderBy('nome_fantasia')->get(),
            'planos' => Plano::query()->orderBy('ordem')->orderBy('nome')->get(),
        ]);
    }

    public function update(LicencaRequest $request, Licenca $licenca): RedirectResponse
    {
        $data = $request->validated();
        $antes = $licenca->only(array_keys($data));
        $this->aplicarDatasDeStatus($data, $licenca);
        $licenca->update($data);
        $this->historico($licenca, 'atualizada', 'Dados da licença atualizados.', ['antes' => $antes, 'depois' => $data]);

        return redirect()->route('master.licencas.index')->with('success', 'Licença atualizada com sucesso.');
    }

    public function renovar(Request $request, Licenca $licenca): RedirectResponse
    {
        $request->validate(['meses' => ['required', 'integer', 'min:1', 'max:60']]);
        $base = $licenca->termina_em && $licenca->termina_em->isFuture() ? $licenca->termina_em : today();
        $licenca->update(['termina_em' => $base->copy()->addMonths($request->integer('meses')), 'status' => Licenca::STATUS_ATIVA, 'suspensa_em' => null, 'cancelada_em' => null]);
        $this->historico($licenca, 'renovada', "Licença renovada por {$request->integer('meses')} mês(es).", ['termina_em' => $licenca->termina_em]);

        return back()->with('success', 'Licença renovada com sucesso.');
    }

    public function regenerarToken(Licenca $licenca, LicencaTokenService $tokenService): RedirectResponse
    {
        $token = $tokenService->gerar($licenca);
        $this->historico($licenca, 'token_rotacionado', 'Token do PontoWeb Agent regenerado.');

        return back()->with('success', 'Novo token gerado. Copie-o agora; ele não será exibido novamente.')->with('agent_token', $token);
    }

    private function novoCodigo(): string
    {
        do {
            $codigo = 'PW-'.Str::upper(Str::random(4).'-'.Str::random(4).'-'.Str::random(4));
        } while (Licenca::query()->where('codigo', $codigo)->exists());

        return $codigo;
    }

    private function aplicarDatasDeStatus(array &$data, ?Licenca $licenca = null): void
    {
        if ($data['status'] === Licenca::STATUS_SUSPENSA) {
            $data['suspensa_em'] = $licenca?->suspensa_em ?? now();
        } else {
            $data['suspensa_em'] = null;
        }

        if ($data['status'] === Licenca::STATUS_CANCELADA) {
            $data['cancelada_em'] = $licenca?->cancelada_em ?? now();
        } else {
            $data['cancelada_em'] = null;
        }
    }

    private function historico(Licenca $licenca, string $tipo, string $descricao, array $dados = []): void
    {
        $licenca->historicos()->create([
            'user_id' => auth()->id(),
            'tipo' => $tipo,
            'descricao' => $descricao,
            'dados' => $dados ?: null,
        ]);
    }
}
