<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Validator;
use App\Models\Status;

final class TaskModel
{
    private int $id;
    private string $title;
    private string $description;
    private Status $status;
    private int $userId;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(int $id, string $title, string $description, int $userId, Status $status = Status::PENDING, \DateTimeImmutable $createdAt = null, \DateTimeImmutable $updatedAt = null)
    {
        if (!Validator::notEmptyString($title)) {
            throw new \InvalidArgumentException("Title cannot be empty.");
        }

        if (Validator::maxLength($title, 255)) {
            throw new \InvalidArgumentException("Title cannot exceed 255 characters.");
        }

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = $status;
        $this->userId = $userId;

        $now = new \DateTimeImmutable();
        $this->createdAt = $createdAt ?? $now;
        $this->updatedAt = $updatedAt ?? $now;
    }

    public function withTitle(string $title): self
    {
        return new self(
            $this->id,
            $title,
            $this->description,
            $this->userId,
            $this->status,
            $this->createdAt,
            new \DateTimeImmutable()
        );
    }

    public function withDescription(string $description): self
    {
        return new self(
            $this->id,
            $this->title,
            $description,
            $this->userId,
            $this->status,
            $this->createdAt,
            new \DateTimeImmutable()
        );
    }

    public function withStatus(Status $status): self
    {
        return new self(
            $this->id,
            $this->title,
            $this->description,
            $this->userId,
            $status,
            $this->createdAt,
            new \DateTimeImmutable()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function __toString(): string
    {
        return sprintf(
            'Task(id=%d, title=%s, description=%s, status=%s, userId=%d, createdAt=%s, updatedAt=%s)',
            $this->id,
            $this->title,
            $this->description,
            $this->status->value,
            $this->userId,
            $this->createdAt->format('Y-m-d H:i:s'),
            $this->updatedAt->format('Y-m-d H:i:s')
        );
    }
}