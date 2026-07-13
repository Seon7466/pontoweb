# PontoWeb — Sprint 1: Base do Painel Master

## Arquivos incluídos

- Middleware de acesso Master.
- Campo `users.is_master`.
- Dashboard global da plataforma.
- Rota protegida `/master/dashboard`.
- Link condicional no menu lateral.
- Estrutura inicial da tabela e model `Plano`.

## Instalação

Copie o conteúdo deste pacote para a raiz do projeto e confirme a substituição.

```powershell
cd C:\Projetos\PontoWeb
composer dump-autoload
php artisan migrate
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

Promova o usuário administrativo, ajustando o ID quando necessário:

```powershell
php artisan tinker --execute="App\Models\User::where('id', 1)->update(['is_master' => true]);"
```

Confirme:

```powershell
php artisan tinker --execute="dump(App\Models\User::find(1, ['id', 'name', 'is_master'])->toArray());"
php artisan route:list --name=master -v
```

Acesse:

```text
http://127.0.0.1:8000/master/dashboard
```

## Atenção às migrations já executadas

As migrations `2026_07_13_152355_create_planos_table.php` e
`2026_07_13_155644_add_is_master_to_users_table.php` estavam vazias no projeto enviado.

Se alguma delas já constar como `Ran` no seu banco, não execute `migrate:fresh`.
Nesse caso, informe o resultado de `php artisan migrate:status` para prepararmos migrations corretivas.
