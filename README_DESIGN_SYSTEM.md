# PontoWeb UI — Design System

## Instalação
Copie as pastas `resources` e o arquivo `tailwind.config.js` para a raiz do projeto.

Execute:
```powershell
cd C:\Projetos\PontoWeb
npm run build
php artisan view:clear
php artisan optimize:clear
```

## Componentes
- `<x-pw.button>`: primary, secondary, success, danger e ghost.
- `<x-pw.card>` e `<x-pw.stat-card>`.
- `<x-pw.badge>`: neutral, success, warning, danger e info.
- `<x-pw.alert>` e `<x-pw.flash>`.
- `<x-pw.page-header>`.
- `<x-pw.table-card>` e `<x-pw.empty-state>`.
- `<x-pw.input>`, `<x-pw.select>`, `<x-pw.textarea>` e `<x-pw.checkbox>`.

## Paleta
- Marca: `brand-600` (`#2563EB`).
- Menu: `slate-900` (`#0F172A`).
- Fundo: `slate-50` (`#F8FAFC`).
- Sucesso: Emerald; alerta: Amber; erro: Red.

## Responsividade
- Desktop: menu de 288 px, recolhível para 80 px.
- Mobile: menu em painel lateral acionado pelo botão ☰.
- Tabelas com rolagem horizontal.

## Git
Após validar:
```powershell
git add .
git commit -m "feat: padroniza interface com PontoWeb UI"
git push
```
