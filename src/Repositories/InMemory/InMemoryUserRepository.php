<?php

declare(strict_types=1);

namespace App\Repositories\InMemory;

use App\Models\UserModel as User;
use App\Repositories\Contracts\UserRepositoryInterface;

final class InMemoryUserRepository implements UserRepositoryInterface
{
    /** @var User[] */
    private array $users = [];

    private int $autoIncrement = 1;

    public function findById(int $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->email === $email) {
                return $user;
            }
        }
        return null;
    }

    public function save(User $user): User
    {
        if ($user->id === null) {
            $user = User::fromDatabase(
                $this->autoIncrement++,
                $user->name,
                $user->email,
                $user->hashPassword,
                $user->createdAt,
                $user->updatedAt,
            );
        }

        $this->users[$user->id] = $user;
        return $user;
    }

    public function delete(int $id): void
    {
        unset($this->users[$id]);
    }
}
