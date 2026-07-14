# PontoWeb PLATFORM-03 — API e Agentes

## Instalação

```powershell
composer dump-autoload
php artisan migrate
php artisan route:clear
php artisan optimize:clear
npm run build
```

## Endpoints

- `POST /api/v1/agent/heartbeat`
- `GET /api/v1/agent/configuration`
- `POST /api/v1/agent/markings`

Use `Authorization: Bearer <TOKEN_DA_LICENCA>`.

## Teste de heartbeat

```powershell
$headers = @{ Authorization = "Bearer SEU_TOKEN" }
$body = @{
  installation_id = "11111111-1111-4111-8111-111111111111"
  name = "Agente RH"
  machine_name = "PC-RH"
  agent_version = "0.1.0"
  os_name = "Windows"
  os_version = "11 Pro"
  local_ip = "192.168.0.10"
} | ConvertTo-Json
Invoke-RestMethod -Method Post -Uri http://127.0.0.1:8001/api/v1/agent/heartbeat -Headers $headers -ContentType "application/json" -Body $body
```

## Segurança

O token é armazenado somente como SHA-256. A API rejeita licença vencida, suspensa, cancelada ou empresa inativa.
