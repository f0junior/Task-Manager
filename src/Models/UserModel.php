<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Validator;

final class UserModel
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $hashPassword,
        public readonly \DateTimeImmutable $createdAt,
        public readonly \DateTimeImmutable $updatedAt,
        public readonly ?int $id = null,
    ) {
    }

    public static function create(string $name, string $email, string $password): self
    {
        if (!Validator::minLength($name, 3)) {
            throw new \InvalidArgumentException("Name must be at least 3 characters long.");
        }

        if (!Validator::isValidEmail($email)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }

        $now = new \DateTimeImmutable();

        return new self(
            $name,
            $email,
            password_hash($password, PASSWORD_BCRYPT),
            $now,
            $now,
        );
    }

    public static function fromDatabase(
        int $id,
        string $name,
        string $email,
        string $hashPassword,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
    ): self {
        return new self(
            $name,
            $email,
            $hashPassword,
            $createdAt,
            $updatedAt,
            $id,
        );
    }

    public function withName(string $name): self
    {
        if (!Validator::minLength($name, 3)) {
            throw new \InvalidArgumentException("Name must be at least 3 characters long.");
        }

        return new self(
            $name,
            $this->email,
            $this->hashPassword,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->id,
        );
    }

    public function withEmail(string $email): self
    {
        if (!Validator::isValidEmail($email)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }

        return new self(
            $this->name,
            $email,
            $this->hashPassword,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->id,
        );
    }

    public function withPassword(string $hashPassword): self
    {
        return new self(
            $this->name,
            $this->email,
            $hashPassword,
            $this->createdAt,
            new \DateTimeImmutable(),
            $this->id,
        );
    }

    public function __toString(): string
    {
        return sprintf(
            "User{id=%d, name='%s', email='%s', createdAt='%s', updatedAt='%s'}",
            $this->id,
            $this->name,
            $this->email,
            $this->createdAt->format('Y-m-d H:i:s'),
            $this->updatedAt->format('Y-m-d H:i:s')
        );
    }
}