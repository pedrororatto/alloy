# 📋 Instruções para rodar o projeto **sem Docker**

## Pré-requisitos
- PHP 8.2+ (com extensões: pdo, pdo_sqlite, intl, zip, redis, etc)
- Composer
- Node.js 18+ e npm
- SQLite

---

## Passos para rodar o projeto

1. **Clone o repositório**
   ```bash
   git clone git@github.com:pedrororatto/alloy.git
   cd alloy
   ```

2. **Instale as dependências do PHP**
   ```bash
   composer install
   ```

3. **Instale as dependências do Node.js**
   ```bash
   npm install
   ```

4. **Configure o ambiente**
   - Copie o arquivo de exemplo:
     ```bash
     cp .env.example .env
     ```
   - Edite o arquivo `.env` e garanta que exista a linha:
     ```env
     DB_CONNECTION=sqlite
     DB_DATABASE=database/database.sqlite
     ```

5. **Crie o arquivo do banco de dados SQLite**
   ```bash
   mkdir -p database
   touch database/database.sqlite
   ```

6. **Gere a chave da aplicação**
   ```bash
   php artisan key:generate
   ```

7. **Rode as migrations**
   ```bash
   php artisan migrate
   ```

8. **Inicie o servidor Laravel**
   ```bash
   php artisan serve
   ```
   - O backend estará disponível em: http://localhost:8000

9. **Inicie o Vite (frontend)**
   Em outro terminal:
   ```bash
   npm run dev
   ```
   - O frontend estará disponível em: http://localhost:5173

10. **(Opcional) Inicie o worker de filas**
    Em outro terminal:
    ```bash
    php artisan queue:work
    ```

---

## Resumo dos Terminais

- **Terminal 1:** `php artisan serve`
- **Terminal 2:** `npm run dev`
- **Terminal 3:** `php artisan queue:work` (opcional, para jobs/filas)

---

Se precisar de instruções para Windows puro ou tiver algum erro de dependência, me avise!
