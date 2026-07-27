<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">

<style>

body{
    font-family: DejaVu Sans;
    font-size:11px;
    color:#222;
    margin:30px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th,td{
    border:1px solid #999;
    padding:5px;
    text-align:center;
}

th{
    background:#efefef;
}

.header{
    text-align:center;
    margin-bottom:25px;
    border-bottom:2px solid #d9d9d9;
    padding-bottom:15px;
}

.footer{
    margin-top:40px;
}

.assinatura{
    width:250px;
    border-top:1px solid #000;
    display:inline-block;
    margin-top:70px;
    text-align:center;
}

</style>

</head>

<body>

<div class="header">

    <h2 style="margin:0;">
        ESPELHO DE PONTO
    </h2>

    <p style="margin-top:12px;">

        <strong>Funcionário:</strong>

        {{ $funcionario->nome }}

    </p>

    <p>

        <strong>Período:</strong>

        {{ $inicio->format('d/m/Y') }}

        até

        {{ $fim->format('d/m/Y') }}

    </p>

    <p>

        <strong>Emitido em:</strong>

        {{ now()->format('d/m/Y H:i') }}

    </p>

</div>

<table>

<thead>

<tr>

<th>Data</th>

<th>Batidas</th>

<th>Previsto</th>

<th>Trabalhado</th>

<th>Saldo</th>

</tr>

</thead>

<tbody>

@foreach($dias as $dia)

<tr>

<td>

{{ $dia['data']->format('d/m/Y') }}

</td>

<td>

@foreach($dia['batidas'] as $batida)

{{ $batida->data_hora->format('H:i') }}

@if(!$loop->last)

|

@endif

@endforeach

</td>

<td>

{{ sprintf('%02dh%02d',intdiv($dia['previstos'],60),$dia['previstos']%60) }}

</td>

<td>

{{ sprintf('%02dh%02d',intdiv($dia['trabalhados'],60),$dia['trabalhados']%60) }}

</td>

<td>

@php

$saldo=abs($dia['saldo']);

@endphp

{{ $dia['saldo']>=0?'+':'-' }}

{{ sprintf('%02dh%02d',intdiv($saldo,60),$saldo%60) }}

</td>

</tr>

@endforeach

</tbody>

</table>

<table style="margin-top:25px;">

<tr>

<th>Total Previsto</th>

<th>Total Trabalhado</th>

<th>Créditos</th>

<th>Débitos</th>

<th>Saldo Final</th>

</tr>

<tr>

<td>

{{ sprintf('%02dh%02d',intdiv($totais['previstos'],60),$totais['previstos']%60) }}

</td>

<td>

{{ sprintf('%02dh%02d',intdiv($totais['trabalhados'],60),$totais['trabalhados']%60) }}

</td>

<td>

{{ sprintf('%02dh%02d',intdiv($totais['creditos'],60),$totais['creditos']%60) }}

</td>

<td>

{{ sprintf('%02dh%02d',intdiv($totais['debitos'],60),$totais['debitos']%60) }}

</td>

<td>

@php

$totalSaldo=abs($totais['saldo']);

@endphp

{{ $totais['saldo']>=0?'+':'-' }}

{{ sprintf('%02dh%02d',intdiv($totalSaldo,60),$totalSaldo%60) }}

</td>

</tr>

</table>

<div class="footer">

<div class="assinatura">

Funcionário

</div>

<div style="float:right" class="assinatura">

Empresa

</div>

</div>

</body>

</html>
