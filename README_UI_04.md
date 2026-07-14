# PontoWeb UI — Sprint 04

Padronização dos módulos **Cargos** e **Horários**.

## Arquivos alterados

- `app/Http/Controllers/CargoController.php`
- `app/Http/Controllers/HorarioController.php`
- `resources/views/cargos/*`
- `resources/views/horarios/*`
- `resources/views/components/pw/search-form.blade.php`
- `resources/views/components/pw/form-actions.blade.php`

## Melhorias

- listagens responsivas e estados vazios;
- pesquisa padronizada;
- formulários organizados por seções;
- contagem de funcionários vinculados;
- carregamento eficiente de departamentos e relações;
- resumo visual de jornada, intervalo e tolerâncias;
- confirmação de exclusão e mensagens de validação;
- uso exclusivo dos componentes PontoWeb UI.

## Aplicação

Copie as pastas `app` e `resources` para a raiz do projeto e execute:

```powershell
composer dump-autoload
npm run build
php artisan view:clear
php artisan optimize:clear
```

Não há migrações nesta sprint.
