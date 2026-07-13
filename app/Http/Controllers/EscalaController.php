<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\EscalaRequest;
use App\Models\Escala;

class EscalaController extends BaseCrudController
{
    protected string $model = Escala::class;
    protected string $view = 'escalas';
    protected string $route = 'escalas';
    protected string $title = 'Escala';
    protected ?string $requestClass = EscalaRequest::class;
    protected bool $tenantScoped = true;

    protected function searchableFields(): array
    {
        return ['descricao', 'tipo'];
    }
}
