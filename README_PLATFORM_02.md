# PontoWeb PLATFORM-02 — Licenças

## Instalação

1. Faça backup do banco.
2. Copie `app`, `bootstrap`, `database`, `resources` e `routes` para o projeto.
3. Execute:

```powershell
composer dump-autoload
php artisan migrate
npm run build
php artisan route:clear
php artisan view:clear
php artisan optimize:clear
```

Acesse `/master/licencas`.

## Segurança

O token do PontoWeb Agent é armazenado somente como SHA-256. O valor completo aparece apenas ao criar ou regenerar o token.

O middleware `licenca.ativa` foi registrado, mas não foi aplicado globalmente nesta entrega para não bloquear o ambiente atual. Ele será ativado após todas as empresas de produção possuírem licença.
