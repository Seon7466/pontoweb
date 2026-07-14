# PontoWeb UI — Micro-sprint 02

## Escopo

- Dashboard da Empresa padronizado com PontoWeb UI.
- Indicadores reais e filtrados por empresa.
- Últimas marcações.
- Situação dos equipamentos.
- Alerta para equipamentos sem comunicação há mais de 10 minutos.
- Atalhos rápidos e estados vazios responsivos.

## Arquivos

- `app/Http/Controllers/DashboardController.php`
- `resources/views/dashboard.blade.php`

## Instalação

Copie as pastas `app` e `resources` para a raiz do projeto e confirme a substituição.

```powershell
cd C:\Projetos\PontoWeb
composer dump-autoload
php artisan view:clear
php artisan optimize:clear
npm run build
```

Reinicie o servidor e abra `/dashboard`.

## Git

```powershell
git add app/Http/Controllers/DashboardController.php resources/views/dashboard.blade.php
git commit -m "feat: moderniza dashboard da empresa"
git push
```
