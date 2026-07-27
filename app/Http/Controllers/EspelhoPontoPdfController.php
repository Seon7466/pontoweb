<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class EspelhoPontoPdfController extends EspelhoPontoController
{
    public function gerar(Request $request): BinaryFileResponse
    {
        $view = parent::index($request);

        return Pdf::loadView(
            'ponto.espelho-pdf',
            $dados
        )
            ->setPaper('a4', 'portrait')
            ->download($nomeArquivo);
    }
}
