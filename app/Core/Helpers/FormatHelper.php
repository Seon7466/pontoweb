<?php

namespace App\Core\Helpers;

class FormatHelper
{
    public static function somenteNumeros($valor)
    {
        return preg_replace('/\D/', '', $valor);
    }

    public static function moeda($valor)
    {
        return number_format($valor, 2, ',', '.');
    }

    public static function cpfCnpj($valor)
    {
        return preg_replace('/\D/', '', $valor);
    }
}
