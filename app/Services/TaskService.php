<?php

namespace App\Services;

use App\Models\Task;
use Illuminate\Support\Facades\Cache;

class TaskService
{
    protected $cacheTtl = 600; // 10 minutos

    public function list()
    {
        return Cache::remember('tasks:list', $this->cacheTtl, function () {
            return Task::whereNull('deleted_at')->orderByDesc('created_at')->get();
        });
    }

    public function find($id)
    {
        return Cache::remember("tasks:{$id}", $this->cacheTtl, function () use ($id) {
            return Task::findOrFail($id);
        });
    }

    public function create(array $data)
    {
        $task = Task::create($data);
        $this->invalidate($task->id);
        return $task;
    }

    public function update(Task $task, array $data)
    {
        $task->update($data);
        $this->invalidate($task->id);
        return $task;
    }

    public function delete(Task $task)
    {
        $task->delete();
        $this->invalidate($task->id);
        return true;
    }

    public function toggle(Task $task)
    {
        $task->finalizado = !$task->finalizado;
        $task->save();
        $this->invalidate($task->id);
        return $task;
    }

    public function invalidate($id = null)
    {
        if ($id) {
            Cache::forget("tasks:{$id}");
        }
        Cache::forget('tasks:list');
    }
}
