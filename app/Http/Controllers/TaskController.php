<?php

namespace App\Http\Controllers;

use App\Services\TaskService;
use App\Models\Task;
use App\Jobs\DeleteCompletedTask;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;

class TaskController extends Controller
{
    protected $service;

    public function __construct(TaskService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $tasks = $this->service->list();
        return response()->json($tasks);
    }

    public function show($id)
    {
        $task = $this->service->find($id);
        return response()->json($task);
    }

    public function store(StoreTaskRequest $request)
    {
        $task = $this->service->create($request->validated());
        return response()->json($task, 201);
    }

    public function update(UpdateTaskRequest $request, $id)
    {
        $task = Task::findOrFail($id);
        $task = $this->service->update($task, $request->validated());
        return response()->json($task);
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $this->service->delete($task);
        return response()->json(['message' => 'Tarefa excluída com sucesso.']);
    }

    public function toggle($id)
    {
        $task = Task::findOrFail($id);
        $task = $this->service->toggle($task);
        if ($task->finalizado) {
            DeleteCompletedTask::dispatch($task)->delay(now()->addMinutes(1));
        }
        return response()->json($task);
    }
}
