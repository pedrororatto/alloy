<?php

namespace App\Jobs;

use App\Models\Task;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\TaskService;

class DeleteCompletedTask implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $task;

    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function handle()
    {
        $task = \App\Models\Task::withTrashed()->find($this->task->id);
        if ($task && $task->finalizado) {
            $task->forceDelete();
            // Invalida o cache após exclusão definitiva
            app(TaskService::class)->invalidate($task->id);
        }
        // Se não encontrar, apenas ignore (não lança exceção)
    }
} 