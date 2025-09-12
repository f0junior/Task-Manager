<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Validator;
use App\Models\Status;

final class TaskModel
{
    public function __construct(
        public readonly string $title,
        public readonly string $description,
        public readonly int $userId,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
        public readonly Status $status = Status::PENDING,
        public readonly ?int $id = null,
    ) {
    }

    public static function create(
        string $title,
        string $description,
        int $userId,
        Status $status = Status::PENDING,
    ): self {
        self::validateTitle($title);

        if (!Validator::maxLength($description, 255)) {
            throw new \InvalidArgumentException("Description must not exceed 255 characters.");
        }

        $now = new \DateTimeImmutable();

        return new self(
            $title,
            $description,
            $userId,
            $now,
            $now,
            $status,
        );
    }

    public static function fromDatabase(
        int $id,
        string $title,
        string $description,
        int $userId,
        Status $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $title,
            $description,
            $userId,
            $createdAt,
            $updatedAt,
            $status,
            $id,
        );
    }

    public static function validateTitle(string $title): void
    {
        if (!Validator::minLength($title, 3)) {
            throw new \InvalidArgumentException("Title must be at least 3 characters long.");
        }

        if (!Validator::maxLength($title, 100)) {
            throw new \InvalidArgumentException("Title must not exceed 100 characters.");
        }
    }

    public function withTitle(string $title): self
    {
        self::validateTitle($title);

        return new self(
            $title,
            $this->description,
            $this->userId,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->status,
            $this->id,
        );
    }

    public function withDescription(string $description): self
    {
        if (!Validator::maxLength($description, 255)) {
            throw new \InvalidArgumentException("Description must not exceed 255 characters.");
        }

        return new self(
            $this->title,
            $description,
            $this->userId,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->status,
            $this->id,
        );
    }

    public function withStatus(Status $status): self
    {
        return new self(
            $this->title,
            $this->description,
            $this->userId,
            $this->createdAt,
            new \DateTimeImmutable(),
            $status,
            $this->id,
        );
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