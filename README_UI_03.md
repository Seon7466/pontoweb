# Sprint UI-03 — Empresas e Departamentos

## Arquivos incluídos

- `resources/views/components/pw/search-form.blade.php`
- `resources/views/components/pw/form-actions.blade.php`
- telas de listagem, criação, edição e formulários de Empresas
- telas de listagem, criação, edição e formulários de Departamentos

## Instalação

Copie a pasta `resources` para `C:\Projetos\PontoWeb` e confirme a substituição.

Depois execute:

```powershell
cd C:\Projetos\PontoWeb
npm run build
php artisan view:clear
php artisan optimize:clear
php artisan serve --host=127.0.0.1 --port=8001
```

Teste:

- `/empresas`
- `/empresas/create` (somente usuário sem empresa vinculada)
- `/departamentos`
- `/departamentos/create`

## Git

```powershell
git add resources/views/components/pw resources/views/empresas resources/views/departamentos
git commit -m "feat: padroniza empresas e departamentos com PontoWeb UI"
git push
```
