<?php

namespace App\Contracts;

use App\Models\Equipamento;

interface RelogioPontoAdapter
{
    public function testarConexao(Equipamento $equipamento): bool;

    /**
     * Retorna marcações normalizadas com, no mínimo:
     * codigo, data_hora e, quando disponível, nsr.
     */
    public function buscarMarcacoes(Equipamento $equipamento, ?\DateTimeInterface $desde = null): iterable;
}
