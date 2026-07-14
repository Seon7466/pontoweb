# PontoWeb — Sprint UI-05

Padronização visual e funcional de **Escalas** e **Funcionários**.

## Arquivos incluídos

- `app/Http/Controllers/EscalaController.php`
- `app/Http/Controllers/FuncionarioController.php`
- `resources/views/escalas/*`
- `resources/views/funcionarios/*`
- `resources/views/components/pw/toolbar.blade.php`
- `resources/views/components/pw/summary-grid.blade.php`

## Instalação

Copie as pastas `app` e `resources` para a raiz do projeto e confirme a substituição.

```powershell
cd C:\Projetos\PontoWeb
composer dump-autoload
npm run build
php artisan view:clear
php artisan optimize:clear
```

Não há migrations novas.

## Testes sugeridos

- `/escalas`
- `/escalas/create`
- edição e exclusão de escala
- `/funcionarios`
- `/funcionarios/create`
- edição, filtro, pesquisa e exclusão de funcionário

## Git

```powershell
git add app/Http/Controllers/EscalaController.php app/Http/Controllers/FuncionarioController.php resources/views
git commit -m "feat: padroniza escalas e funcionarios com PontoWeb UI"
git push
```
