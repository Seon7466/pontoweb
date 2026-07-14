# PontoWeb — Sprint UI-06

## Módulos atualizados

- Equipamentos
- Registro de ponto

## Instalação

Copie as pastas `app` e `resources` para a raiz do projeto em `C:\Projetos\PontoWeb`, confirmando a substituição.

Execute:

```powershell
cd C:\Projetos\PontoWeb
composer dump-autoload
npm run build
php artisan view:clear
php artisan optimize:clear
```

Inicie o servidor:

```powershell
php artisan serve --host=127.0.0.1 --port=8001
```

Teste:

- http://127.0.0.1:8001/equipamentos
- http://127.0.0.1:8001/equipamentos/create
- http://127.0.0.1:8001/ponto

Não há migrações novas nesta sprint.
