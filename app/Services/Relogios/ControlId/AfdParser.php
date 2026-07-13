<?php

namespace App\Services\Relogios\ControlId;

use Carbon\CarbonImmutable;

class AfdParser
{
    /**
     * Interpreta registros tipo 3 do AFD da Portaria 671 e o formato legado.
     * Linhas desconhecidas são ignoradas, nunca inventadas.
     */
    public function parse(string $afd, string $timezone = 'America/Sao_Paulo'): array
    {
        $resultados = [];
        $linhas = preg_split('/\r\n|\n|\r/', $afd) ?: [];

        foreach ($linhas as $linha) {
            $linha = trim($linha);
            if ($linha === '' || strlen($linha) < 10 || substr($linha, 9, 1) !== '3') {
                continue;
            }

            $registro = $this->parse671($linha, $timezone) ?? $this->parseLegado($linha, $timezone);
            if ($registro !== null) {
                $registro['linha_bruta'] = $linha;
                $resultados[] = $registro;
            }
        }

        return $resultados;
    }

    private function parse671(string $linha, string $timezone): ?array
    {
        if (strlen($linha) < 50) {
            return null;
        }

        $nsr = trim(substr($linha, 0, 9));
        $dataHora = trim(substr($linha, 10, 24));
        $cpf = preg_replace('/\D/', '', substr($linha, 34, 12));

        $data = $this->parseDateTime671($dataHora, $timezone);
        if (! $data || strlen((string) $cpf) < 11) {
            return null;
        }

        return ['nsr' => $nsr, 'codigo' => ltrim((string) $cpf, '0') ?: '0', 'data_hora' => $data];
    }

    private function parseLegado(string $linha, string $timezone): ?array
    {
        if (strlen($linha) < 34) {
            return null;
        }

        $nsr = trim(substr($linha, 0, 9));
        $data = substr($linha, 10, 8);
        $hora = substr($linha, 18, 4);
        $pis = preg_replace('/\D/', '', substr($linha, 22, 12));

        try {
            $dataHora = CarbonImmutable::createFromFormat('dmYHi', $data.$hora, $timezone);
        } catch (\Throwable) {
            return null;
        }

        return ['nsr' => $nsr, 'codigo' => ltrim((string) $pis, '0') ?: '0', 'data_hora' => $dataHora];
    }

    private function parseDateTime671(string $value, string $timezone): ?CarbonImmutable
    {
        foreach (['Y-m-d\TH:i:sO', 'Y-m-d\TH:i:sP', 'Y-m-d\TH:i:s', 'Y-m-d H:i:sO', 'Y-m-d H:i:sP', 'Y-m-d H:i:s'] as $format) {
            try {
                $date = CarbonImmutable::createFromFormat($format, $value, $timezone);
                if ($date !== false) {
                    return $date;
                }
            } catch (\Throwable) {
            }
        }

        try {
            return CarbonImmutable::parse($value, $timezone);
        } catch (\Throwable) {
            return null;
        }
    }
}
