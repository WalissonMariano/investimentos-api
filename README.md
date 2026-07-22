# Investimentos API

API e painel administrativo do **Termômetro do Poder de Compra** — comparação entre Dólar, CDI e IPCA para avaliar se o capital ganhou ou perdeu poder de compra.

Stack: **Laravel 10**, **PostgreSQL**, **JWT** (API) e autenticação por sessão (painel web).

## Requisitos

- PHP 8.1+
- Composer
- PostgreSQL
- Extensões PHP comuns do Laravel (pdo_pgsql, openssl, mbstring, etc.)

## Instalação

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan jwt:secret
```

Configure o banco no `.env`:

```env
APP_URL=http://investimentos-api.test

DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=investimentos-api
DB_USERNAME=postgres
DB_PASSWORD=sua_senha

JWT_TTL=1440
```

Rode as migrations:

```bash
php artisan migrate
```

Isso cria as tabelas e o usuário admin padrão.

## Usuário admin (painel)

| Campo  | Valor             |
|--------|-------------------|
| E-mail | `admin@admin.com` |
| Senha  | `admin`           |

## Painel web

| Rota        | Descrição              |
|-------------|------------------------|
| `/login`    | Login do administrador |
| `/menu`     | Shell com sidebar + iframe |
| `/dashboard`| Dashboard de exemplo   |
| `/users`    | Listagem de usuários   |

Autenticação do painel: guard **`web`** (sessão).

## API (JWT)

Login de integração (e-mail + `secret_token`):

```http
POST /api/auth/login
Content-Type: application/json

{
  "email": "admin@admin.com",
  "secret_token": "admin"
}
```

Resposta: `auth_token` (JWT) + dados do usuário.

Rotas protegidas usam:

```http
Authorization: Bearer {auth_token}
```

Exemplo:

```http
GET /api/auth
```

## Estrutura inicial

- `app/Http/Controllers/Auth` — login/logout do painel
- `app/Http/Controllers/Api` — autenticação JWT
- `resources/views` — login, menu, dashboard e usuários
- `planejamento.txt` — ideias do termômetro e fontes de dados (BCB / AwesomeAPI)

## Próximos passos

- Endpoint do termômetro (IPCA × CDI × Dólar)
- CRUD completo de usuários e empresas
- Documentação OpenAPI da API
