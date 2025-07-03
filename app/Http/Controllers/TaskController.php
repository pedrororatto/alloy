<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\Jobs\DeleteCompletedTask;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::whereNull('deleted_at')->orderByDesc('created_at')->get();
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return response()->json($task);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'finalizado' => 'boolean',
            'data_limite' => 'nullable|date',
        ]);
        $task = Task::create($data);
        return response()->json($task, 201);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string',
            'finalizado' => 'boolean',
            'data_limite' => 'nullable|date',
        ]);
        $task->update($data);
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();
        return response()->json(['message' => 'Tarefa excluída com sucesso.']);
    }

    public function toggle($id)
    {
        $task = Task::findOrFail($id);
        $task->finalizado = !$task->finalizado;
        $task->save();
        if ($task->finalizado) {
            DeleteCompletedTask::dispatch($task)->delay(now()->addMinutes(10));
        }
        return response()->json($task);
    }
} 