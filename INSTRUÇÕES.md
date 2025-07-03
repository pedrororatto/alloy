# 📋 Instruções de Uso e Documentação da API

## Como rodar o projeto (Docker)

### Pré-requisitos
- Docker e Docker Compose instalados

### Passos rápidos

1. **Clone o repositório:**
   ```bash
   git clone git@github.com:pedrororatto/alloy.git
   ```

2. **Configure o arquivo de ambiente (.env):**
   - Copie o arquivo de exemplo:
     ```bash
     cp .env.example .env
     ```
   - Edite o arquivo `.env` e garanta que exista a linha:
     ```env
     DB_DATABASE=database/database.sqlite
     ```

3. **Suba os containers:**
   ```bash
   docker-compose up --build
   ```

4. **Acesse:**
   - **Frontend + Backend:** [http://localhost:8000](http://localhost:8000)
   - **Frontend Vite (dev):** [http://localhost:5173](http://localhost:5173)

5. **Rodando migrations (primeira vez):**
   Em outro terminal:
   ```bash
   docker-compose exec app php artisan migrate
   ```

6. **Queue Worker:**
   Já sobe automaticamente via serviço `queue` no Docker Compose.

---

## Documentação da API

Base URL: `http://localhost:8000/api`

### Endpoints

#### 1. Listar tarefas
- **GET** `/api/tasks`
- **Resposta:** Array de tarefas (não excluídas)
- **Cache:** 10 minutos

#### 2. Visualizar tarefa específica
- **GET** `/api/tasks/{id}`
- **Resposta:** Objeto da tarefa

#### 3. Criar tarefa
- **POST** `/api/tasks`
- **Body (JSON):**
  ```json
  {
    "nome": "string (obrigatório, até 255)",
    "descricao": "string (opcional)",
    "finalizado": false, // opcional, default: false
    "data_limite": "YYYY-MM-DD HH:MM:SS" // opcional
  }
  ```
- **Resposta:** Objeto da tarefa criada

#### 4. Atualizar tarefa
- **PUT/PATCH** `/api/tasks/{id}`
- **Body (JSON):**
  ```json
  {
    "nome": "string (obrigatório, até 255)",
    "descricao": "string (opcional)",
    "finalizado": true, // ou false
    "data_limite": "YYYY-MM-DD HH:MM:SS" // opcional
  }
  ```
- **Resposta:** Objeto da tarefa atualizada

#### 5. Excluir tarefa (soft delete)
- **DELETE** `/api/tasks/{id}`
- **Resposta:** `{ "message": "Tarefa excluída com sucesso." }`

#### 6. Alternar status de finalização
- **PATCH** `/api/tasks/{id}/toggle`
- **Resposta:** Objeto da tarefa com status `finalizado` alternado
- **Observação:** Ao marcar como finalizada, um job é agendado para exclusão definitiva em 10 minutos.

---

### Validações

- `nome`: obrigatório, string, máximo 255 caracteres
- `descricao`: opcional, string
- `finalizado`: booleano
- `data_limite`: opcional, data/hora

---

### Exemplo de Objeto de Tarefa

```json
{
  "id": 1,
  "nome": "Comprar pão",
  "descricao": "Ir à padaria até 18h",
  "finalizado": false,
  "data_limite": "2024-06-10 18:00:00",
  "created_at": "2024-06-09T12:00:00.000000Z",
  "updated_at": "2024-06-09T12:00:00.000000Z",
  "deleted_at": null
}
```

---

### Observações Técnicas

- **Cache:** Listagem e visualização de tarefas são cacheadas por 10 minutos. O cache é invalidado automaticamente em qualquer alteração (criação, edição, exclusão, toggle).
- **Soft Delete:** Exclusão via API apenas marca como excluída (`deleted_at`). Exclusão definitiva ocorre via job agendado para tarefas finalizadas.
- **Fila:** O job de exclusão definitiva roda automaticamente via worker (serviço `queue` no Docker Compose).
- **Banco:** SQLite, arquivo em `/database/database.sqlite` (persistido via volume Docker).

---

## Testes

Para rodar os testes:
```bash
docker-compose exec app php artisan test
```

---
