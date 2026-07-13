<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $permissoes = [
            'empresas.ver',
            'empresas.criar',
            'empresas.editar',
            'empresas.excluir',

            'departamentos.ver',
            'departamentos.criar',
            'departamentos.editar',
            'departamentos.excluir',

            'cargos.ver',
            'cargos.criar',
            'cargos.editar',
            'cargos.excluir',

            'funcionarios.ver',
            'funcionarios.criar',
            'funcionarios.editar',
            'funcionarios.excluir',

            'ponto.bater',
            'ponto.ajustar',
            'ponto.aprovar',

            'relatorios.ver',
            'configuracoes.editar',
        ];

        foreach ($permissoes as $permissao) {
            Permission::firstOrCreate(['name' => $permissao]);
        }

        $admin = Role::firstOrCreate(['name' => 'Administrador']);
        $rh = Role::firstOrCreate(['name' => 'RH']);
        $gestor = Role::firstOrCreate(['name' => 'Gestor']);
        $funcionario = Role::firstOrCreate(['name' => 'Funcionario']);

        $admin->syncPermissions($permissoes);

        $rh->syncPermissions([
            'empresas.ver',
            'departamentos.ver',
            'departamentos.criar',
            'departamentos.editar',
            'cargos.ver',
            'cargos.criar',
            'cargos.editar',
            'funcionarios.ver',
            'funcionarios.criar',
            'funcionarios.editar',
            'ponto.ajustar',
            'ponto.aprovar',
            'relatorios.ver',
        ]);

        $gestor->syncPermissions([
            'funcionarios.ver',
            'ponto.ajustar',
            'ponto.aprovar',
            'relatorios.ver',
        ]);

        $funcionario->syncPermissions([
            'ponto.bater',
        ]);
    }
}
