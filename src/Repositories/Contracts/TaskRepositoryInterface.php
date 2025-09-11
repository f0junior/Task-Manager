<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\TaskModel as Task;

interface TaskRepositoryInterface
{
    public function findById(int $id): ?Task;

    /** @return Task[] */
    public function findByUserId(int $userId): array;

    public function save(Task $task): Task;

    public function delete(int $id): void;
}