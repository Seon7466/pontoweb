<?php

namespace App\Services\Relogios\ControlId;

use App\Contracts\RelogioPontoAdapter;
use App\Models\Equipamento;
use DateTimeInterface;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Throwable;

class ControlIdClassAdapter implements RelogioPontoAdapter
{
    public function __construct(
        private readonly AfdParser $parser
    ) {
    }

    public function testarConexao(
        Equipamento $equipamento
    ): bool {
        $session = $this->login($equipamento);

        try {
            $about = $this->obterJson(
                $equipamento,
                '/get_about.fcgi',
                $session
            );

            $system = $this->obterJson(
                $equipamento,
                '/get_system_information.fcgi',
                $session
            );

            $equipamento->forceFill([
                'numero_serie' => $about['nSerie']
                    ?? $equipamento->numero_serie,

                'mac' => $about['mac']
                    ?? $equipamento->mac,

                'versao_firmware' => isset($about['versionFW'])
                    ? (string) $about['versionFW']
                    : $equipamento->versao_firmware,

                'ultimo_nsr' => isset($system['last_nsr'])
                    && is_numeric($system['last_nsr'])
                        ? (int) $system['last_nsr']
                        : $equipamento->ultimo_nsr,

                'ultima_conexao_em' => now(),
                'ultima_falha_em' => null,

                'ultima_mensagem' =>
                    'Conexão com o REP iDClass realizada com sucesso.',
            ])->save();

            return true;
        } finally {
            $this->logoutSilencioso(
                $equipamento,
                $session
            );
        }
    }

    /**
     * @return iterable<int, array{
     *     codigo: string,
     *     data_hora: mixed,
     *     nsr: mixed,
     *     dados_brutos: array{afd: mixed}
     * }>
     */
    public function buscarMarcacoes(
        Equipamento $equipamento,
        ?DateTimeInterface $desde = null
    ): iterable {
        $session = $this->login($equipamento);

        try {
            $payload = $this->montarFiltroMarcacoes(
                $equipamento,
                $desde
            );

            $response = $this->post(
                $equipamento,
                '/get_afd.fcgi',
                $payload,
                $session,
                true
            );

            $marcacoes = $this->parser->parse(
                $response->body(),
                $equipamento->timezone ?: 'America/Sao_Paulo'
            );

            foreach ($marcacoes as $marcacao) {
                yield [
                    'codigo' => (string) $marcacao['codigo'],
                    'data_hora' => $marcacao['data_hora'],
                    'nsr' => $marcacao['nsr'] ?? null,
                    'dados_brutos' => [
                        'afd' => $marcacao['linha_bruta'] ?? null,
                    ],
                ];
            }
        } finally {
            $this->logoutSilencioso(
                $equipamento,
                $session
            );
        }
    }

    /**
     * @return array{
     *     about: array<string, mixed>,
     *     system: array<string, mixed>
     * }
     */
    public function obterInformacoes(
        Equipamento $equipamento
    ): array {
        $session = $this->login($equipamento);

        try {
            return [
                'about' => $this->obterJson(
                    $equipamento,
                    '/get_about.fcgi',
                    $session
                ),

                'system' => $this->obterJson(
                    $equipamento,
                    '/get_system_information.fcgi',
                    $session
                ),
            ];
        } finally {
            $this->logoutSilencioso(
                $equipamento,
                $session
            );
        }
    }

    private function login(
        Equipamento $equipamento
    ): string {
        $this->validarConfiguracao($equipamento);

        try {
            $response = $this->client($equipamento)
                ->post(
                    $equipamento->baseUrl().'/login.fcgi',
                    [
                        'login' => $equipamento->usuario_api ?: 'admin',
                        'password' => $equipamento->senha_api ?: 'admin',
                    ]
                );
        } catch (ConnectionException $exception) {
            throw new ControlIdException(
                'Não foi possível conectar ao iDClass.',
                previous: $exception
            );
        }

        $session = $response->json('session');

        if (
            ! $response->successful()
            || ! is_string($session)
            || $session === ''
        ) {
            throw new ControlIdException(
                'Falha no login do iDClass. Verifique IP, porta, usuário, senha e SSL.'
            );
        }

        return $session;
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function post(
        Equipamento $equipamento,
        string $path,
        array $payload,
        string $session,
        bool $modo671
    ): Response {
        $query = [
            'session' => $session,
        ];

        if ($modo671 && $equipamento->modo_671) {
            $query['mode'] = 671;
        }

        try {
            $response = $this->client($equipamento)
                ->withQueryParameters($query)
                ->post(
                    $equipamento->baseUrl().$path,
                    $payload
                );
        } catch (ConnectionException $exception) {
            throw new ControlIdException(
                sprintf(
                    'Não foi possível acessar o endpoint %s do iDClass.',
                    $path
                ),
                previous: $exception
            );
        }

        if (! $response->successful()) {
            throw new ControlIdException(
                sprintf(
                    'O iDClass respondeu HTTP %d em %s.',
                    $response->status(),
                    $path
                )
            );
        }

        return $response;
    }

    /**
     * @return array<string, mixed>
     */
    private function obterJson(
        Equipamento $equipamento,
        string $path,
        string $session
    ): array {
        $response = $this->post(
            $equipamento,
            $path,
            [],
            $session,
            false
        );

        $dados = $response->json();

        if (! is_array($dados)) {
            throw new ControlIdException(
                sprintf(
                    'O iDClass retornou uma resposta inválida em %s.',
                    $path
                )
            );
        }

        return $dados;
    }

    private function client(
        Equipamento $equipamento
    ): PendingRequest {
        $timeout = (int) (
            $equipamento->timeout_segundos ?: 15
        );

        $client = Http::acceptJson()
            ->asJson()
            ->timeout($timeout)
            ->connectTimeout(min(10, $timeout))
            ->retry(
                times: 2,
                sleepMilliseconds: 300,
                throw: false
            )
            ->withOptions([
                'expect' => false,
            ]);

        return $equipamento->verificar_ssl
            ? $client
            : $client->withoutVerifying();
    }

    private function logoutSilencioso(
        Equipamento $equipamento,
        string $session
    ): void {
        try {
            $this->post(
                $equipamento,
                '/logout.fcgi',
                [],
                $session,
                false
            );
        } catch (Throwable) {
            // O encerramento da sessão não deve ocultar
            // o resultado da operação principal.
        }
    }

    /**
     * @return array<string, mixed>
     */
    private function montarFiltroMarcacoes(
        Equipamento $equipamento,
        ?DateTimeInterface $desde
    ): array {
        if ($equipamento->ultimo_nsr) {
            return [
                'initial_nsr' => (int) $equipamento->ultimo_nsr + 1,
            ];
        }

        if ($desde) {
            return [
                'initial_date' => [
                    'day' => (int) $desde->format('d'),
                    'month' => (int) $desde->format('m'),
                    'year' => (int) $desde->format('Y'),
                ],
            ];
        }

        return [];
    }

    private function validarConfiguracao(
        Equipamento $equipamento
    ): void {
        if (! $equipamento->ip) {
            throw new ControlIdException(
                'Informe o endereço IP do equipamento.'
            );
        }

        if (! $equipamento->isControlIdClass()) {
            throw new ControlIdException(
                'O equipamento não está identificado como Control iD / iDClass.'
            );
        }

        if (
            ! in_array(
                $equipamento->protocolo,
                ['http', 'https'],
                true
            )
        ) {
            throw new ControlIdException(
                'O protocolo do equipamento deve ser HTTP ou HTTPS.'
            );
        }
    }
}
