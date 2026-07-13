<?php

namespace App\Http\Controllers;

use App\Core\Controllers\BaseCrudController;
use App\Http\Requests\FuncionarioRequest;
use App\Models\Funcionario;

class FuncionarioController extends BaseCrudController
{
    protected string $model = Funcionario::class;
    protected string $view = 'funcionarios';
    protected string $route = 'funcionarios';
    protected string $title = 'Funcionário';
    protected ?string $requestClass = FuncionarioRequest::class;
    protected bool $tenantScoped = true;

    protected function searchableFields(): array
    {
        return [
            'nome',
            'cpf',
            'matricula',
            'email',
            'telefone',
        ];
    }
}
