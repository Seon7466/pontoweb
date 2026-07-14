# Entrega de estabilização — testes, erros e documentação

## PontoWeb Laravel

Copie o conteúdo de `PontoWeb/` para `C:\Projetos\PontoWeb` e confirme a substituição.

```powershell
cd C:\Projetos\PontoWeb
composer dump-autoload
php artisan optimize:clear
npm run build
php artisan test
```

Os testes usam SQLite em memória; não alteram o MySQL local.

## PontoWeb Agent

Copie `PontoWebAgent/` para `C:\Projetos\PontoWebAgent`.

```powershell
Stop-Service PontoWebAgent -ErrorAction SilentlyContinue
Stop-Process -Name PontoWeb.Agent -Force -ErrorAction SilentlyContinue
cd C:\Projetos\PontoWebAgent
Set-ExecutionPolicy -Scope Process Bypass
.\scripts\testar.ps1
```

## Conteúdo

- testes de isolamento multiempresa e cadastro;
- testes de acesso Master;
- testes de autenticação e heartbeat da API;
- testes de configuração e cliente HTTP do Agent;
- `X-Request-ID` para rastreamento;
- erros JSON padronizados;
- páginas amigáveis de erro web;
- documentação de instalação, testes, Agent, logs e produção.
