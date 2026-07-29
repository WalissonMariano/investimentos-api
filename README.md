# Investimentos API

![Investimentos Api](docs/banner/banner-api-investimentos.png)

API REST e painel administrativo do **Termômetro do Poder de Compra**.

O projeto compara um capital em reais com três cenários no mesmo período:

| Cenário | Pergunta |
|---------|----------|
| **Caixa** | O dinheiro parado manteve o poder de compra? |
| **CDI / Selic** | Quanto renderia nos juros de mercado? |
| **Dólar** | Quanto renderia parado em USD (marcado em R$)? |

O ganho mais relevante é o **ganho real** (descontado o IPCA): se o retorno ficou abaixo da inflação, houve perda de poder de compra.

---

## Stack

| Tecnologia | Uso |
|------------|-----|
| **Laravel 10** | Backend e painel web |
| **PHP 8.1+** | Runtime |
| **PostgreSQL** | Banco de dados |
| **JWT** (`php-open-source-saver/jwt-auth`) | Autenticação da API |
| **Sessão web** | Login do painel admin |
| **AwesomeAPI** | Cotações USD/BRL |
| **Banco Central (SGS)** | Séries Selic e IPCA |

---

## Requisitos

- PHP 8.1+ com extensões do Laravel (`pdo_pgsql`, `openssl`, `mbstring`, `tokenizer`, `xml`, `ctype`, `json`, `bcmath`)
- Composer
- PostgreSQL
- (Opcional) Laravel Herd / Valet / Sail para servir a aplicação

---

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Configure o `.env`:

```env
APP_NAME=InvestimentosAPI
APP_URL=http://investimentos-api.test

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=investimentos-api
DB_USERNAME=postgres
DB_PASSWORD=sua_senha

JWT_TTL=1440
JWT_ALGO=HS256

BASE_URL_AWESOMEAPI=https://economia.awesomeapi.com.br/json/
BASE_URL_BANCO_CENTRAL=https://api.bcb.gov.br/dados/serie/
```

Rode as migrations:

```bash
php artisan migrate
```

Isso cria as tabelas (`users`, `api_request_logs`, etc.) e o usuário administrador padrão.

---

## Usuário administrador (painel web)

| Campo | Valor |
|-------|-------|
| E-mail | `admin@admin.com` |
| Senha | `admin` |
| Grupo | `admin` |

> Apenas usuários com `user_group = admin` podem acessar o painel web.

Para a **API**, o mesmo usuário autentica com:

| Campo | Valor |
|-------|-------|
| E-mail | `admin@admin.com` |
| Secret token | `admin` |

---

## Arquitetura em resumo

```text
Cliente HTTP / Postman
        │
        ▼
   /api/*  (JWT + ForceJsonResponse)
        │
        ├── AuthController          → login / me
        ├── TermometroController    → valida e orquestra
        │         │
        │         ▼
        │   TermometroService
        │         ├── AwesomeApiService      (USD/BRL)
        │         └── BancoCentralApiService (Selic + IPCA)
        │
        └── ApiRequestLog (grava uso da API)

Painel web (/menu, iframe)
        │
        ├── DashboardController  → métricas de api_request_logs
        └── UserController       → CRUD de usuários
```

---

## Painel web

Autenticação: guard **`web`** (sessão).

| Rota | Descrição |
|------|-----------|
| `/login` | Login do administrador |
| `/menu` | Shell com sidebar + iframe |
| `/dashboard` | KPIs e atividade com base em `api_request_logs` |
| `/users` | Listagem de usuários |
| `/users/create` | Novo usuário |
| `/users/{id}/edit` | Editar usuário |

### Dashboard

O dashboard consome a tabela `api_request_logs` e exibe:

- Consultas ao termômetro (30 dias)
- Usuários ativos
- Tokens emitidos (`auth.login`)
- Taxa de sucesso (`status_code < 500`)
- Volume por tipo de ação
- Atividade recente
- Consumo por usuário no mês

### Grupos de usuário

| Grupo | Acesso |
|-------|--------|
| `admin` | Painel web + API |
| `user` | Em geral apenas API (login web bloqueado) |

---

## API REST

Prefixo: **`/api`**

Todas as rotas da API forçam resposta JSON (`ForceJsonResponse`), mesmo sem header `Accept: application/json`.

### Autenticação

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@admin.com",
  "secret_token": "admin"
}
```

**Resposta 200:**

```json
{
  "auth_token": "eyJ...",
  "user": {
    "id": "...",
    "email": "admin@admin.com",
    "name": "admin",
    "token_timeout_in_seconds": 86400
  }
}
```

Rotas protegidas:

```http
Authorization: Bearer {auth_token}
```

### Endpoints

| Método | Rota | Auth | Descrição |
|--------|------|------|-----------|
| `POST` | `/api/auth/login` | Não | Emite JWT |
| `GET` | `/api/auth` | Sim | Dados do usuário autenticado |
| `GET` | `/api/termometro` | Sim | Calcula o termômetro |

### Termômetro

```http
GET /api/termometro?valor=10000&dataInicio=2024-01-01&dataFim=2024-12-31
Authorization: Bearer {auth_token}
```

| Parâmetro | Tipo | Regras |
|-----------|------|--------|
| `valor` | number | obrigatório, > 0 |
| `dataInicio` | date | `Y-m-d` |
| `dataFim` | date | `Y-m-d`, ≥ `dataInicio` |

**Resposta (formato):**

```json
{
  "periodo": { "inicio": "2024-01-01", "fim": "2024-12-31" },
  "capital_inicial_brl": 10000,
  "fatores": {
    "ipca": 1.05,
    "cdi": 1.10,
    "usd": 1.12
  },
  "cenarios": {
    "caixa":  { "final_nominal": 10000, "poder_compra": 9523.81, "ganho_real": -476.19 },
    "cdi":    { "final_nominal": 11000, "poder_compra": 10476.19, "ganho_real": 476.19 },
    "dolar":  { "final_nominal": 11200, "poder_compra": 10666.67, "ganho_real": 666.67 }
  },
  "veredito": {
    "melhor_cenario": "dolar",
    "cdi_bateu_ipca": true,
    "dolar_bateu_ipca": true,
    "dolar_bateu_cdi": true
  }
}
```

### Fontes de dados do termômetro

| Indicador | Fonte | Série / endpoint |
|-----------|--------|------------------|
| USD/BRL | AwesomeAPI | `/json/daily/USD-BRL` |
| Selic (proxy de CDI no campo `cdi`) | BCB SGS | série `11` |
| IPCA | BCB SGS | série `10843` |

> O campo `cdi` da resposta usa a Selic over (série 11) como proxy. A série oficial de CDI no BCB é a `12`, se quiser trocar depois.

### Erros comuns da API

| Status | Quando |
|--------|--------|
| `401` | Token ausente/inválido ou credenciais incorretas |
| `422` | Validação falhou (body/query inválidos) |
| `404` | Rota inexistente em `/api/*` |
| `500` | Falha ao consultar fontes externas ou calcular |

---

## Logs de API (`api_request_logs`)

Cada chamada relevante grava um registro:

| Action | Origem |
|--------|--------|
| `auth.login` | Login API com sucesso |
| `auth.failed` | Login API inválido |
| `auth.me` | `GET /api/auth` |
| `termometro` | `GET /api/termometro` (200 ou 500) |

Campos principais: `user_id`, `action`, `method`, `endpoint`, `status_code`, `ip`, `meta`, `created_at`.

---

## Estrutura do código

```text
app/
├── Http/
│   ├── Controllers/
│   │   ├── Api/
│   │   │   ├── AuthController.php
│   │   │   └── TermometroController.php
│   │   ├── Auth/LoginController.php
│   │   ├── DashboardController.php
│   │   ├── UserController.php
│   │   └── ApiRequestLogController.php
│   ├── Middleware/ForceJsonResponse.php
│   └── Requests/
│       ├── LoginRequest.php
│       └── TermometroRequest.php
├── Models/
│   ├── User.php
│   └── ApiRequestLog.php
└── Services/
    ├── AwesomeApi/AwesomeApiService.php
    ├── BancoCentral/BancoCentralApiService.php
    └── Termometro/TermometroService.php

resources/views/
├── auth/login.blade.php
├── dashboard/dashboard.blade.php
├── error/not-found.blade.php
├── layout/menu.blade.php
└── users/
    ├── form-users.blade.php
    └── index-users.blade.php
```

---

## Segurança

- Painel: sessão Laravel + CSRF; login restrito a `admin`
- API: JWT Bearer; `secret_token` e senha com hash
- Grupo `api` com throttle e `ForceJsonResponse`
- `.env` fora do versionamento (use `.env.example` como modelo)

---

## Documentação Swagger

Interface interativa da API:

```text
{APP_URL}/api/documentation
```

Exemplo local: `http://investimentos-api.test/api/documentation`

Para regenerar a documentação após alterar anotações:

```bash
php artisan l5-swagger:generate
```

No Swagger UI, use **Authorize** e informe o JWT no formato:

```text
Bearer {auth_token}
```

(ou apenas o token, conforme a UI pedir no campo Bearer).

---

## Testes rápidos

```bash
# Login API
curl -X POST "$APP_URL/api/auth/login" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"admin@admin.com","secret_token":"admin"}'

# Termômetro (substitua TOKEN)
curl "$APP_URL/api/termometro?valor=10000&dataInicio=2024-01-01&dataFim=2024-12-31" \
  -H "Authorization: Bearer TOKEN" \
  -H "Accept: application/json"
```

---

## Roadmap sugerido

- [ ] Trocar série Selic (11) pela série CDI (12), se desejado
- [ ] Cache local das séries BCB/AwesomeAPI
- [x] Documentação OpenAPI (Swagger)
- [ ] Testes automatizados dos services e endpoints
- [ ] Versionamento da API (`/api/v1`)
