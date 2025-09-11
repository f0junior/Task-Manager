<?php

declare(strict_types=1);

namespace App\Models;

use App\Helpers\Validator;

final class UserModel
{
    private int $id;
    private string $name;
    private string $email;
    private string $hashPassword;
    private \DateTimeImmutable $createdAt;
    private \DateTimeImmutable $updatedAt;

    public function __construct(int $id, string $name, string $email, string $hashPassword, \DateTimeImmutable $createdAt = null, \DateTimeImmutable $updatedAt = null)
    {
        if (Validator::minLength($name, 3)) {
            throw new \InvalidArgumentException("Name must be at least 3 characters long.");
        }

        if (!Validator::isValidEmail($email)) {
            throw new \InvalidArgumentException("Invalid email format.");
        }

        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->hashPassword = $hashPassword;

        $now = new \DateTimeImmutable();
        $this->createdAt = $createdAt ?? $now;
        $this->updatedAt = $updatedAt ?? $now;
    }

    public function withName(string $name): self
    {
        return new self(
            $this->id,
            $name,
            $this->email,
            $this->hashPassword,
            $this->createdAt,
            new \DateTimeImmutable()
        );
    }

    public function withEmail(string $email): self
    {
        return new self(
            $this->id,
            $this->name,
            $email,
            $this->hashPassword,
            $this->createdAt,
            new \DateTimeImmutable()
        );
    }

    public function withPassword(string $hashPassword): self
    {
        return new self(
            $this->id,
            $this->name,
            $this->email,
            $hashPassword,
            $this->createdAt,
            new \DateTimeImmutable()
        );
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function verifyPassword(string $password): bool
    {
        return password_verify($password, $this->hashPassword);
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
            "User{id=%d, name='%s', email='%s', createdAt='%s', updatedAt='%s'}",
            $this->id,
            $this->name,
            $this->email,
            $this->createdAt->format('Y-m-d H:i:s'),
            $this->updatedAt->format('Y-m-d H:i:s')
        );
    }
}