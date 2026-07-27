<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Agente;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AgenteController extends Controller
{
    public function index(Request $request): View
    {
        $onlineDesde = now()->subMinutes(5);
        $versaoAtual = config('app.agent_latest_version', '0.1.0');

        $query = Agente::query()
            ->with(['empresa', 'licenca'])
            ->orderByDesc('last_seen_at');

        if ($request->filled('search')) {
            $search = $request->string('search')->trim()->toString();

            $query->where(function ($query) use ($search) {
                $query
                    ->where('machine_name', 'like', "%{$search}%")
                    ->orWhere('nome', 'like', "%{$search}%")
                    ->orWhere('agent_version', 'like', "%{$search}%")
                    ->orWhereHas('empresa', function ($empresa) use ($search) {
                        $empresa
                            ->where('nome_fantasia', 'like', "%{$search}%")
                            ->orWhere('razao_social', 'like', "%{$search}%");
                    });
            });
        }

        $status = $request->string('status')->toString();

        if ($status === 'online') {
            $query->where('last_seen_at', '>=', $onlineDesde);
        }

        if ($status === 'offline') {
            $query->where(function ($query) use ($onlineDesde) {
                $query
                    ->whereNull('last_seen_at')
                    ->orWhere('last_seen_at', '<', $onlineDesde);
            });
        }

        $agentes = $query
            ->paginate(20)
            ->withQueryString();

        $indicadores = [
            'total' => Agente::query()->count(),

            'online' => Agente::query()
                ->where('last_seen_at', '>=', $onlineDesde)
                ->count(),

            'offline' => Agente::query()
                ->where(function ($query) use ($onlineDesde) {
                    $query
                        ->whereNull('last_seen_at')
                        ->orWhere('last_seen_at', '<', $onlineDesde);
                })
                ->count(),

            'desatualizados' => Agente::query()
                ->whereNotNull('agent_version')
                ->where('agent_version', '!=', $versaoAtual)
                ->count(),
        ];

        return view(
            'master.agentes.index',
            compact('agentes', 'indicadores', 'versaoAtual')
        );
    }

    public function show(Agente $agente): View
    {
        $agente->load([
            'empresa',
            'licenca.plano',
        ]);

        $logs = $agente
            ->logs()
            ->latest('occurred_at')
            ->paginate(50);

        return view(
            'master.agentes.show',
            compact('agente', 'logs')
        );
    }
}
