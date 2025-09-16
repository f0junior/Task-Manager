<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Status;
use App\Models\TaskModel;
use App\Repositories\Contracts\TaskRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;

final class TaskService
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private UserRepositoryInterface $userRepository,
    ) {
    }

    public function create(int $userId, string $title, ?string $description = null): TaskModel
    {
        $this->userExistsOrFail($userId);

        $task = TaskModel::create($title, $description ?? '', $userId);

        return $this->taskRepository->save($task);
    }

    public function getTasksByUserId(int $userId): array
    {
        $this->userExistsOrFail($userId);

        return $this->taskRepository->findByUserId($userId);
    }

    public function updateTitle(int $taskId, string $newTitle): TaskModel
    {
        $task = $this->getTaskFromIdOrFail($taskId);

        $updatedTask = $task->withTitle($newTitle);
        return $this->taskRepository->save($updatedTask);
    }

    public function updateDescription(int $taskId, string $newDescription): TaskModel
    {
        $task = $this->getTaskFromIdOrFail($taskId);

        $updatedTask = $task->withDescription($newDescription);
        return $this->taskRepository->save($updatedTask);
    }

    public function updateStatus(int $taskId, string $newStatus): TaskModel
    {
        $task = $this->getTaskFromIdOrFail($taskId);
        $status = Status::from($newStatus);
        if ($task->status === $status) {
            return $task;
        }

        $updatedTask = $task->withStatus($status);
        return $this->taskRepository->save($updatedTask);
    }

    private function getTaskFromIdOrFail(int $taskId): TaskModel
    {
        $task = $this->taskRepository->findById($taskId);
        if ($task === null) {
            throw new \InvalidArgumentException("Task with ID $taskId does not exist.");
        }

        return $task;
    }

    private function userExistsOrFail(int $userId): void
    {
        if (!$this->userRepository->findById($userId)) {
            throw new \InvalidArgumentException("User with ID $userId does not exist.");
        }
    }
}