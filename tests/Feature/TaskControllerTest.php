<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Task;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    public function test_example(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function test_crud_flow()
    {
        // Criar tarefa
        $response = $this->postJson('/api/tasks', [
            'nome' => 'Tarefa Teste',
            'descricao' => 'Descrição',
            'finalizado' => false,
            'data_limite' => now()->addDay()->toDateTimeString(),
        ]);
        $response->assertStatus(201);
        $taskId = $response->json('id');

        // Listar tarefas
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(200)->assertJsonFragment(['nome' => 'Tarefa Teste']);

        // Visualizar tarefa
        $response = $this->getJson("/api/tasks/{$taskId}");
        $response->assertStatus(200)->assertJson(['id' => $taskId]);

        // Atualizar tarefa
        $response = $this->putJson("/api/tasks/{$taskId}", [
            'nome' => 'Tarefa Editada',
            'descricao' => 'Nova descrição',
            'finalizado' => true,
            'data_limite' => now()->addDays(2)->toDateTimeString(),
        ]);
        $response->assertStatus(200)->assertJson(['nome' => 'Tarefa Editada']);

        // Deletar tarefa
        $response = $this->deleteJson("/api/tasks/{$taskId}");
        $response->assertStatus(200);
        $this->assertSoftDeleted('tasks', ['id' => $taskId]);
    }
}
