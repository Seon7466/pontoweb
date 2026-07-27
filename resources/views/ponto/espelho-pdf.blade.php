<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">

    <title>Cartão Ponto</title>

    <style>
        @page {
            size: A4 portrait;
            margin: 5mm;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 6.8px;
            line-height: 1.12;
            color: #0f1f3d;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        /*
        |--------------------------------------------------------------------------
        | Cabeçalho
        |--------------------------------------------------------------------------
        */

        .cabecalho {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 5px;
            padding-bottom: 5px;
            border-bottom: 1.2px solid #0047b9;
        }

        .cabecalho td {
            padding: 0 3px;
            vertical-align: middle;
        }

        .marca {
            width: 28%;
        }

        .marca-nome {
            margin: 0;
            color: #0047b9;
            font-size: 20px;
            font-weight: bold;
            letter-spacing: -0.8px;
        }

        .marca-subtitulo {
            margin-top: 1px;
            color: #0047b9;
            font-size: 5.2px;
            text-transform: uppercase;
        }

        .titulo-documento {
            width: 43%;
            text-align: center;
        }

        .titulo-documento h1 {
            margin: 0;
            color: #0f1f3d;
            font-size: 17px;
            font-weight: bold;
        }

        .titulo-documento p {
            margin: 3px 0 0;
            font-size: 7.8px;
        }

        .empresa-cabecalho {
            width: 29%;
            text-align: right;
        }

        .empresa-cabecalho .nome {
            color: #0047b9;
            font-size: 7.7px;
            font-weight: bold;
            line-height: 1.15;
            overflow-wrap: break-word;
            text-transform: uppercase;
        }

        .empresa-cabecalho p {
            margin: 2px 0 0;
            font-size: 5.9px;
        }

        /*
        |--------------------------------------------------------------------------
        | Informações do funcionário
        |--------------------------------------------------------------------------
        */

        .informacoes {
            width: 100%;
            table-layout: fixed;
            margin-bottom: 5px;
        }

        .informacoes > tbody > tr > td {
            vertical-align: top;
        }

        .dados-funcionario {
            width: 54%;
            padding-right: 4px;
        }

        .horario-trabalho {
            width: 46%;
            padding-left: 4px;
        }

        .linha-campo {
            width: 100%;
            table-layout: fixed;
            border-bottom: 0.4px solid #b9c2d0;
        }

        .linha-campo td {
            height: 16px;
            padding: 2px 3px;
            vertical-align: middle;
        }

        .rotulo {
            color: #0047b9;
            font-size: 5.4px;
            font-weight: bold;
            line-height: 1;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .valor {
            color: #0f1f3d;
            font-size: 6px;
            line-height: 1.08;
            overflow-wrap: break-word;
            text-transform: uppercase;
        }

        .largura-rotulo {
            width: 17%;
        }

        .largura-valor {
            width: 33%;
        }

        /*
        |--------------------------------------------------------------------------
        | Horário de trabalho
        |--------------------------------------------------------------------------
        */

        .titulo-horario {
            padding: 4px;
            background: #0047b9;
            color: #ffffff;
            font-size: 6.4px;
            font-weight: bold;
            text-align: center;
            text-transform: uppercase;
        }

        .tabela-horario {
            width: 100%;
            table-layout: fixed;
            border: 0.4px solid #b9c2d0;
        }

        .tabela-horario th,
        .tabela-horario td {
            height: 13px;
            padding: 1.5px 1px;
            border: 0.35px solid #cbd2dc;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
        }

        .tabela-horario th {
            background: #f1f5fa;
            color: #0f1f3d;
            font-size: 5px;
            font-weight: bold;
        }

        .tabela-horario td {
            font-size: 5.4px;
        }

        /*
        |--------------------------------------------------------------------------
        | Tabela principal
        |--------------------------------------------------------------------------
        */

        .tabela-ponto {
            width: 100%;
            table-layout: fixed;
        }

        .tabela-ponto thead {
            display: table-header-group;
        }

        .tabela-ponto tr {
            page-break-inside: avoid;
        }

        .tabela-ponto thead th {
            height: 15px;
            padding: 2px 0.5px;
            border: 0.3px solid #ffffff;
            background: #0047b9;
            color: #ffffff;
            font-size: 4.9px;
            font-weight: bold;
            line-height: 1;
            text-align: center;
            text-transform: uppercase;
            white-space: nowrap;
        }

        .tabela-ponto tbody td {
            height: 13px;
            padding: 1px 0.5px;
            overflow: hidden;
            border: 0.3px solid #d2d7df;
            font-size: 5.4px;
            line-height: 1;
            text-align: center;
            white-space: nowrap;
        }

        .tabela-ponto tbody tr:nth-child(even) {
            background: #f4f6f9;
        }

        .tabela-ponto .linha-totais td {
            height: 14px;
            background: #edf3fb;
            font-size: 5.5px;
            font-weight: bold;
        }

        /*
        |--------------------------------------------------------------------------
        | Largura das colunas — total de 100%
        |--------------------------------------------------------------------------
        */

        .col-data {
            width: 9%;
        }

        .col-dia {
            width: 5%;
        }

        .col-marcacao {
            width: 8%;
        }

        .col-resultado {
            width: 9%;
        }

        .saldo-positivo {
            color: #0047d7;
            font-weight: bold;
        }

        .saldo-negativo {
            color: #e00000;
            font-weight: bold;
        }

        .saldo-neutro {
            color: #0f1f3d;
        }

        /*
        |--------------------------------------------------------------------------
        | Assinaturas
        |--------------------------------------------------------------------------
        */

        .assinaturas {
            width: 100%;
            table-layout: fixed;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .assinaturas td {
            width: 50%;
            padding: 0 30px;
            vertical-align: top;
            text-align: center;
        }

        .linha-assinatura {
            width: 88%;
            margin: 0 auto;
            border-top: 0.8px solid #0047b9;
        }

        .nome-assinatura {
            margin-top: 5px;
            color: #222222;
            font-size: 6.5px;
            line-height: 1.1;
            overflow-wrap: break-word;
            text-transform: uppercase;
        }

        .tipo-assinatura {
            margin-top: 2px;
            color: #444444;
            font-size: 5.6px;
            line-height: 1;
            text-transform: uppercase;
        }

        /*
        |--------------------------------------------------------------------------
        | Rodapé
        |--------------------------------------------------------------------------
        */

        .rodape {
            width: 100%;
            margin-top: 9px;
            padding-top: 4px;
            border-top: 0.7px solid #0047b9;
            color: #0047b9;
            font-size: 5.2px;
            line-height: 1.15;
            text-align: center;
        }

        .rodape strong {
            display: block;
            margin-top: 2px;
            font-size: 6px;
        }
    </style>
</head>

<body>

@php
    /*
    |--------------------------------------------------------------------------
    | Empresa
    |--------------------------------------------------------------------------
    */

    $empresa = $funcionario->empresa;

    $nomeEmpresa = $empresa->nome
        ?? $empresa->razao_social
        ?? 'Empresa não informada';

    $cnpjEmpresa = $empresa->cnpj
        ?? 'Não informado';

    $inscricaoEmpresa = $empresa->inscricao_estadual
        ?? $empresa->inscricao
        ?? 'Não informado';

    $nomeProprietario = $empresa->proprietario
        ?? $empresa->nome_proprietario
        ?? $empresa->responsavel
        ?? $empresa->responsavel_legal
        ?? $empresa->representante_legal
        ?? $nomeEmpresa;

    /*
    |--------------------------------------------------------------------------
    | Funcionário
    |--------------------------------------------------------------------------
    */

    $nomeFuncionario = $funcionario->nome
        ?? 'Funcionário não informado';

    $matricula = $funcionario->matricula
        ?? $funcionario->codigo_relogio
        ?? 'Não informado';

    $cpf = $funcionario->cpf
        ?? 'Não informado';

    $ctps = $funcionario->ctps
        ?? 'Não informado';

    $cargo = $funcionario->cargo->nome
        ?? 'Não informado';

    $departamento = $funcionario->departamento->nome
        ?? 'Não informado';

    $admissao = 'Não informado';

    if (!empty($funcionario->data_admissao)) {
        $admissao = \Carbon\Carbon::parse(
            $funcionario->data_admissao
        )->format('d/m/Y');
    }

    $horario = $funcionario->horario;

    /*
    |--------------------------------------------------------------------------
    | Formatação de minutos
    |--------------------------------------------------------------------------
    */

    $formatarMinutos = function (
        $minutos,
        $comSinal = false
    ) {
        $minutos = (int) $minutos;

        $sinal = '';

        if ($comSinal && $minutos !== 0) {
            $sinal = $minutos < 0 ? '-' : '+';
        }

        $minutosAbsolutos = abs($minutos);

        $horas = intdiv($minutosAbsolutos, 60);
        $restante = $minutosAbsolutos % 60;

        return $sinal
            .str_pad(
                (string) $horas,
                2,
                '0',
                STR_PAD_LEFT
            )
            .':'
            .str_pad(
                (string) $restante,
                2,
                '0',
                STR_PAD_LEFT
            );
    };

    /*
    |--------------------------------------------------------------------------
    | Dias da semana
    |--------------------------------------------------------------------------
    */

    $diasSemana = [
        0 => 'DOMINGO',
        1 => 'SEGUNDA',
        2 => 'TERÇA',
        3 => 'QUARTA',
        4 => 'QUINTA',
        5 => 'SEXTA',
        6 => 'SÁBADO',
    ];

    $diasSemanaCurto = [
        0 => 'Dom',
        1 => 'Seg',
        2 => 'Ter',
        3 => 'Qua',
        4 => 'Qui',
        5 => 'Sex',
        6 => 'Sáb',
    ];

    /*
    |--------------------------------------------------------------------------
    | Formatação de horários
    |--------------------------------------------------------------------------
    */

    $formatarHorario = function ($valor) {
        if (empty($valor)) {
            return '-';
        }

        try {
            return \Carbon\Carbon::parse(
                $valor
            )->format('H:i');
        } catch (\Throwable $exception) {
            return substr(
                (string) $valor,
                0,
                5
            );
        }
    };

    $entradaHorario = $horario
        ? $formatarHorario($horario->entrada)
        : '-';

    $saidaIntervaloHorario = $horario
        ? $formatarHorario($horario->inicio_intervalo)
        : '-';

    $retornoIntervaloHorario = $horario
        ? $formatarHorario($horario->fim_intervalo)
        : '-';

    $saidaHorario = $horario
        ? $formatarHorario($horario->saida)
        : '-';
@endphp

{{-- Cabeçalho --}}
<table class="cabecalho">
    <tr>
        <td class="marca">
            <div class="marca-nome">
                pontoweb
            </div>

            <div class="marca-subtitulo">
                Tecnologia em ponto eletrônico
            </div>
        </td>

        <td class="titulo-documento">
            <h1>CARTÃO PONTO</h1>

            <p>
                Período:
                {{ $inicio->format('d/m/Y') }}
                até
                {{ $fim->format('d/m/Y') }}
            </p>
        </td>

        <td class="empresa-cabecalho">
            <div class="nome">
                {{ $nomeEmpresa }}
            </div>

            <p>
                CNPJ: {{ $cnpjEmpresa }}
            </p>

            <p>
                Inscrição: {{ $inscricaoEmpresa }}
            </p>
        </td>
    </tr>
</table>

{{-- Informações --}}
<table class="informacoes">
    <tr>
        <td class="dados-funcionario">

            <table class="linha-campo">
                <tr>
                    <td class="rotulo largura-rotulo">
                        Empresa:
                    </td>

                    <td class="valor" colspan="3">
                        {{ $nomeEmpresa }}
                    </td>
                </tr>
            </table>

            <table class="linha-campo">
                <tr>
                    <td class="rotulo largura-rotulo">
                        CNPJ:
                    </td>

                    <td class="valor largura-valor">
                        {{ $cnpjEmpresa }}
                    </td>

                    <td class="rotulo largura-rotulo">
                        Inscrição:
                    </td>

                    <td class="valor largura-valor">
                        {{ $inscricaoEmpresa }}
                    </td>
                </tr>
            </table>

            <table class="linha-campo">
                <tr>
                    <td class="rotulo largura-rotulo">
                        Nome:
                    </td>

                    <td class="valor largura-valor">
                        {{ $nomeFuncionario }}
                    </td>

                    <td class="rotulo largura-rotulo">
                        Nº folha:
                    </td>

                    <td class="valor largura-valor">
                        {{ $matricula }}
                    </td>
                </tr>
            </table>

            <table class="linha-campo">
                <tr>
                    <td class="rotulo">
                        C.T.P.S.:
                    </td>

                    <td class="valor">
                        {{ $ctps }}
                    </td>

                    <td class="rotulo">
                        CPF:
                    </td>

                    <td class="valor">
                        {{ $cpf }}
                    </td>

                    <td class="rotulo">
                        Admissão:
                    </td>

                    <td class="valor">
                        {{ $admissao }}
                    </td>
                </tr>
            </table>

            <table class="linha-campo">
                <tr>
                    <td class="rotulo largura-rotulo">
                        Função:
                    </td>

                    <td class="valor largura-valor">
                        {{ $cargo }}
                    </td>

                    <td class="rotulo largura-rotulo">
                        Departamento:
                    </td>

                    <td class="valor largura-valor">
                        {{ $departamento }}
                    </td>
                </tr>
            </table>

            <table class="linha-campo">
                <tr>
                    <td class="rotulo largura-rotulo">
                        Observação:
                    </td>

                    <td class="valor">
                        -
                    </td>
                </tr>
            </table>

        </td>

        <td class="horario-trabalho">

            <div class="titulo-horario">
                Horário de trabalho
            </div>

            <table class="tabela-horario">
                <thead>
                    <tr>
                        <th>Dia</th>
                        <th>Entrada 1</th>
                        <th>Saída 1</th>
                        <th>Entrada 2</th>
                        <th>Saída 2</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($diasSemana as $numeroDia => $nomeDia)
                        @php
                            $fimDeSemana = in_array(
                                $numeroDia,
                                [0, 6],
                                true
                            );
                        @endphp

                        <tr>
                            <td>
                                {{ $nomeDia }}
                            </td>

                            @if ($fimDeSemana)
                                <td>Folga</td>
                                <td>Folga</td>
                                <td>Folga</td>
                                <td>Folga</td>
                            @else
                                <td>{{ $entradaHorario }}</td>
                                <td>{{ $saidaIntervaloHorario }}</td>
                                <td>{{ $retornoIntervaloHorario }}</td>
                                <td>{{ $saidaHorario }}</td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
            </table>

        </td>
    </tr>
</table>

{{-- Tabela principal --}}
<table class="tabela-ponto">
    <thead>
        <tr>
            <th class="col-data">Data</th>
            <th class="col-dia">Dia</th>

            <th class="col-marcacao">
                Entrada 1
            </th>

            <th class="col-marcacao">
                Saída 1
            </th>

            <th class="col-marcacao">
                Entrada 2
            </th>

            <th class="col-marcacao">
                Saída 2
            </th>

            <th class="col-resultado">
                Normais
            </th>

            <th class="col-resultado">
                Faltas
            </th>

            <th class="col-resultado">
                EX50%
            </th>

            <th class="col-resultado">
                EX100%
            </th>

            <th class="col-resultado">
                Saldo
            </th>

            <th class="col-resultado">
                Carga
            </th>
        </tr>
    </thead>

    <tbody>
        <tr class="linha-totais">
            <td>TOTAIS</td>

            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>

            <td>
                {{ $formatarMinutos(
                    $totais['trabalhados']
                ) }}
            </td>

            <td>
                {{ $formatarMinutos(
                    $totais['debitos']
                ) }}
            </td>

            <td>
                {{ $formatarMinutos(
                    $totais['creditos']
                ) }}
            </td>

            <td>00:00</td>

            <td
                class="{{
                    $totais['saldo'] > 0
                        ? 'saldo-positivo'
                        : (
                            $totais['saldo'] < 0
                                ? 'saldo-negativo'
                                : 'saldo-neutro'
                        )
                }}"
            >
                {{ $formatarMinutos(
                    $totais['saldo'],
                    true
                ) }}
            </td>

            <td>
                {{ $formatarMinutos(
                    $totais['previstos']
                ) }}
            </td>
        </tr>

        @foreach ($dias as $dia)
            @php
                $batidasDia = $dia['batidas']->values();

                $entrada1 = $batidasDia->get(0);
                $saida1 = $batidasDia->get(1);
                $entrada2 = $batidasDia->get(2);
                $saida2 = $batidasDia->get(3);

                $numeroDiaSemana =
                    $dia['data']->dayOfWeek;

                $ehDomingo =
                    $numeroDiaSemana === 0;

                $semBatidas =
                    $batidasDia->isEmpty();

                $exibirFolga =
                    $ehDomingo && $semBatidas;

                $formatarBatida = function ($batida) {
                    if (
                        !$batida
                        || !$batida->data_hora
                    ) {
                        return '-';
                    }

                    return $batida
                        ->data_hora
                        ->format('H:i');
                };

                $classeSaldo = $dia['saldo'] > 0
                    ? 'saldo-positivo'
                    : (
                        $dia['saldo'] < 0
                            ? 'saldo-negativo'
                            : 'saldo-neutro'
                    );
            @endphp

            <tr>
                <td>
                    {{ $dia['data']->format('d/m/Y') }}
                </td>

                <td>
                    {{ $diasSemanaCurto[$numeroDiaSemana] }}
                </td>

                @if ($exibirFolga)
                    <td>Folga</td>
                    <td>Folga</td>
                    <td>Folga</td>
                    <td>Folga</td>
                @else
                    <td>{{ $formatarBatida($entrada1) }}</td>
                    <td>{{ $formatarBatida($saida1) }}</td>
                    <td>{{ $formatarBatida($entrada2) }}</td>
                    <td>{{ $formatarBatida($saida2) }}</td>
                @endif

                <td>
                    {{ $formatarMinutos(
                        $dia['trabalhados']
                    ) }}
                </td>

                <td>
                    {{ $dia['debitos'] > 0
                        ? $formatarMinutos(
                            $dia['debitos']
                        )
                        : ''
                    }}
                </td>

                <td>
                    {{ $dia['creditos'] > 0
                        ? $formatarMinutos(
                            $dia['creditos']
                        )
                        : ''
                    }}
                </td>

                <td></td>

                <td class="{{ $classeSaldo }}">
                    {{ $dia['saldo'] !== 0
                        ? $formatarMinutos(
                            $dia['saldo'],
                            true
                        )
                        : $formatarMinutos(
                            $dia['trabalhados']
                        )
                    }}
                </td>

                <td>
                    {{ $dia['previstos'] > 0
                        ? $formatarMinutos(
                            $dia['previstos']
                        )
                        : ''
                    }}
                </td>
            </tr>
        @endforeach
    </tbody>
</table>

{{-- Assinaturas --}}
<table class="assinaturas">
    <tr>
        <td>
            <div class="linha-assinatura"></div>

            <div class="nome-assinatura">
                {{ strtoupper($nomeFuncionario) }}
            </div>

            <div class="tipo-assinatura">
                FUNCIONÁRIO
            </div>
        </td>

        <td>
            <div class="linha-assinatura"></div>

            <div class="nome-assinatura">
                {{ strtoupper($nomeProprietario) }}
            </div>

            <div class="tipo-assinatura">
                PROPRIETÁRIO DO ESTABELECIMENTO
            </div>
        </td>
    </tr>
</table>

{{-- Rodapé --}}
<div class="rodape">
    Documento emitido eletronicamente pelo PontoWeb -
    Sistema de Ponto Eletrônico

    <strong>PontoWeb</strong>
</div>

</body>

</html>
