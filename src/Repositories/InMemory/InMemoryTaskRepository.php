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
            fn(Task $task) => $task->getUserId() === $userId
        );
    }

    public function save(Task $task): Task
    {
        if ($task->getId() === 0) {
            // Simula ID auto increment
            $reflection = new \ReflectionClass($task);
            $property = $reflection->getProperty('id');
            $property->setAccessible(true);
            $property->setValue($task, $this->autoIncrement++);
        }

        $this->tasks[$task->getId()] = $task;
        return $task;
    }

    public function delete(int $id): void
    {
        unset($this->tasks[$id]);
    }
}
