<?php

namespace App\Actions\Empresa;

use App\Models\Empresa;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class CriarAdministrador
{
    public function execute(Empresa $empresa, array $dados): User
    {
        return User::create([
            'empresa_id' => $empresa->id,
            'name'       => $dados['admin_name'],
            'email'      => $dados['admin_email'],
            'password'   => Hash::make($dados['admin_password']),
            'is_master'  => false,
        ]);
    }
}
