<?php

declare(strict_types=1);

namespace App\Repositories\InMemory;

use App\Models\TaskModel as Task;
use App\Repositories\Contracts\TaskRepositoryInterface;

final class InMemoryTaskRepository implements TaskRepositoryInterface
{
    /** @var Task[] */
    private array $tasks = [];

    private int $autoIncrement = 1;

    public function findById(int $id): ?Task
    {
        return $this->tasks[$id] ?? null;
    }

    /** @return Task[] */
    public function findByUserId(int $userId): array
    {
        return array_filter(
            $this->tasks,
            fn(Task $task) => $task->userId === $userId
        );
    }

    public function save(Task $task): Task
    {
        if ($task->id === null) {
            // Simula ID auto increment
            $task = Task::fromDatabase(
                $this->autoIncrement++,
                $task->title,
                $task->description,
                $task->userId,
                $task->status,
                $task->createdAt,
                $task->updatedAt,
            );
        }

        $this->tasks[$task->id] = $task;
        return $task;
    }

    public function delete(int $id): void
    {
        unset($this->tasks[$id]);
    }
}
