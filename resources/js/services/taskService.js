// Serviço para comunicação com a API de tarefas
const API_URL = '/api/tasks';

export async function getTasks() {
    const res = await fetch(API_URL);
    if (!res.ok) throw new Error('Erro ao buscar tarefas');
    return await res.json();
}

export async function getTask(id) {
    const res = await fetch(`${API_URL}/${id}`);
    if (!res.ok) throw new Error('Erro ao buscar tarefa');
    return await res.json();
}

export async function createTask(data) {
    const res = await fetch(API_URL, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error('Erro ao criar tarefa');
    return await res.json();
}

export async function updateTask(id, data) {
    const res = await fetch(`${API_URL}/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify(data),
    });
    if (!res.ok) throw new Error('Erro ao atualizar tarefa');
    return await res.json();
}

export async function deleteTask(id) {
    const res = await fetch(`${API_URL}/${id}`, {
        method: 'DELETE' });
    if (!res.ok) throw new Error('Erro ao excluir tarefa');
    return await res.json();
}

export async function toggleTask(id) {
    const res = await fetch(`${API_URL}/${id}/toggle`, {
        method: 'PATCH',
    });
    if (!res.ok) throw new Error('Erro ao alternar tarefa');
    return await res.json();
} 