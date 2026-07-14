# Sprint PLATFORM-01 — Planos

## Entrega

- CRUD Master de planos comerciais.
- Planos Starter, Basic, Business e Enterprise por seeder.
- Limites de funcionários, relógios e armazenamento.
- Recursos por plano: API, aplicativo, BI, geolocalização, integrações, backup e Marketplace.
- Suporte e ordenação comercial.
- Valores nulos representam "Ilimitado" ou "Sob consulta".
- Acesso protegido por `auth`, `verified` e `master`.

## Instalação

```powershell
cd C:\Projetos\PontoWeb
composer dump-autoload
php artisan migrate
php artisan db:seed --class=PlanoSeeder
npm run build
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

Acesse:

- `http://127.0.0.1:8001/master/planos`
- `http://127.0.0.1:8001/master/planos/create`

## Atenção

A migration `2026_07_13_235900_expand_planos_for_saas_platform.php` é corretiva: funciona tanto quando a tabela `planos` ainda não existe quanto quando a migration antiga já foi executada.
