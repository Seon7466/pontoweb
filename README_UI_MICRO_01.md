# PontoWeb UI — Micro-sprint 01

Arquivos alterados:

- `resources/views/layouts/app.blade.php`
- `resources/views/layouts/app/sidebar.blade.php`
- `resources/views/layouts/app/topbar.blade.php`
- `resources/views/layouts/app/footer.blade.php`

## Aplicação

Copie a pasta `resources` para a raiz do projeto em `C:\Projetos\PontoWeb`, confirmando a substituição.

Depois execute:

```powershell
cd C:\Projetos\PontoWeb
npm run build
php artisan view:clear
php artisan optimize:clear
php artisan serve --host=127.0.0.1 --port=8001
```

Teste:

- `/dashboard`
- `/master/dashboard`
- `/empresas`
- `/departamentos`
- `/perfil`

## Git

Após validar:

```powershell
git add resources/views/layouts
git commit -m "feat: padroniza layout base do PontoWeb UI"
git push
```
