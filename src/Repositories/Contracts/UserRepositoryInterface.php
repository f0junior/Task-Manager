<?php

declare(strict_types=1);

namespace App\Repositories\Contracts;

use App\Models\UserModel as User;

interface UserRepositoryInterface
{
    public function findById(int $id): ?User;

    public function findByEmail(string $email): ?User;

    public function save(User $user): User;

    public function delete(int $id): void;
}