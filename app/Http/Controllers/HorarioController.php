<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\HorarioRequest;
use App\Models\Horario;

class HorarioController extends BaseCrudController
{
    protected string $model = Horario::class;
    protected string $view = 'horarios';
    protected string $route = 'horarios';
    protected string $title = 'Horário';
    protected ?string $requestClass = HorarioRequest::class;
    protected bool $tenantScoped = true;

    protected function searchableFields(): array
    {
        return ['descricao'];
    }
}
