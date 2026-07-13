<?php

namespace App\Services\Relogios\ControlId;

use App\Contracts\RelogioPontoAdapter;
use App\Models\Equipamento;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class ControlIdClassAdapter implements RelogioPontoAdapter
{
    public function __construct(private readonly AfdParser $parser)
    {
    }

    public function testarConexao(Equipamento $equipamento): bool
    {
        $session = $this->login($equipamento);

        try {
            $about = $this->post($equipamento, '/get_about.fcgi', [], $session, false)->json();
            $system = $this->post($equipamento, '/get_system_information.fcgi', [], $session, false)->json();

            $equipamento->forceFill([
                'numero_serie' => $about['nSerie'] ?? $equipamento->numero_serie,
                'mac' => $about['mac'] ?? $equipamento->mac,
                'versao_firmware' => isset($about['versionFW']) ? (string) $about['versionFW'] : $equipamento->versao_firmware,
                'ultimo_nsr' => $system['last_nsr'] ?? $equipamento->ultimo_nsr,
                'ultima_conexao_em' => now(),
                'ultima_mensagem' => 'Conexão com o REP iDClass realizada com sucesso.',
            ])->save();

            return true;
        } finally {
            $this->logoutSilencioso($equipamento, $session);
        }
    }

    public function buscarMarcacoes(Equipamento $equipamento, ?\DateTimeInterface $desde = null): iterable
    {
        $session = $this->login($equipamento);

        try {
            $payload = [];
            if ($equipamento->ultimo_nsr) {
                $payload['initial_nsr'] = $equipamento->ultimo_nsr + 1;
            } elseif ($desde) {
                $payload['initial_date'] = [
                    'day' => (int) $desde->format('d'),
                    'month' => (int) $desde->format('m'),
                    'year' => (int) $desde->format('Y'),
                ];
            }

            $response = $this->post($equipamento, '/get_afd.fcgi', $payload, $session, true);
            $afd = $response->body();
            $marcacoes = $this->parser->parse($afd, $equipamento->timezone ?: 'America/Sao_Paulo');

            foreach ($marcacoes as $marcacao) {
                yield [
                    'codigo' => $marcacao['codigo'],
                    'data_hora' => $marcacao['data_hora'],
                    'nsr' => $marcacao['nsr'],
                    'dados_brutos' => ['afd' => $marcacao['linha_bruta']],
                ];
            }
        } finally {
            $this->logoutSilencioso($equipamento, $session);
        }
    }

    public function obterInformacoes(Equipamento $equipamento): array
    {
        $session = $this->login($equipamento);
        try {
            return [
                'about' => $this->post($equipamento, '/get_about.fcgi', [], $session, false)->json(),
                'system' => $this->post($equipamento, '/get_system_information.fcgi', [], $session, false)->json(),
            ];
        } finally {
            $this->logoutSilencioso($equipamento, $session);
        }
    }

    private function login(Equipamento $equipamento): string
    {
        $this->validarConfiguracao($equipamento);
        $response = $this->client($equipamento)
            ->post($equipamento->baseUrl().'/login.fcgi', [
                'login' => $equipamento->usuario_api ?: 'admin',
                'password' => $equipamento->senha_api ?: 'admin',
            ]);

        if (! $response->successful() || ! $response->json('session')) {
            throw new ControlIdException('Falha no login do iDClass. Verifique IP, porta, usuário, senha e SSL.');
        }

        return (string) $response->json('session');
    }

    private function post(Equipamento $equipamento, string $path, array $payload, string $session, bool $mode671)
    {
        $query = ['session' => $session];
        if ($mode671 && $equipamento->modo_671) {
            $query['mode'] = 671;
        }

        $response = $this->client($equipamento)
            ->withQueryParameters($query)
            ->post($equipamento->baseUrl().$path, $payload);

        if (! $response->successful()) {
            throw new ControlIdException(sprintf('O iDClass respondeu HTTP %d em %s.', $response->status(), $path));
        }

        return $response;
    }

    private function client(Equipamento $equipamento): PendingRequest
    {
        $client = Http::acceptJson()
            ->asJson()
            ->timeout($equipamento->timeout_segundos ?: 15)
            ->connectTimeout(min(10, $equipamento->timeout_segundos ?: 15))
            ->retry(2, 300, throw: false)
            ->withOptions(['expect' => false]);

        return $equipamento->verificar_ssl ? $client : $client->withoutVerifying();
    }

    private function logoutSilencioso(Equipamento $equipamento, string $session): void
    {
        try {
            $this->post($equipamento, '/logout.fcgi', [], $session, false);
        } catch (\Throwable) {
        }
    }

    private function validarConfiguracao(Equipamento $equipamento): void
    {
        if (! $equipamento->ip) {
            throw new ControlIdException('Informe o endereço IP do equipamento.');
        }
        if (! $equipamento->isControlIdClass()) {
            throw new ControlIdException('O equipamento não está identificado como Control iD / iDClass.');
        }
    }
}
