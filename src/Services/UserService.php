<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\UserModel;
use App\Repositories\Contracts\UserRepositoryInterface;

final class UserService
{
    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {
    }

    public function register(string $name, string $email, string $password): UserModel
    {
        $existingUser = $this->userRepository->findByEmail($email);
        if ($existingUser !== null) {
            throw new \InvalidArgumentException("Email already in use.");
        }

        $hashPassword = password_hash($password, PASSWORD_BCRYPT);
        $user = UserModel::create($name, $email, $hashPassword);
        return $this->userRepository->save($user);
    }

    public function login(string $email, string $password): UserModel
    {
        $user = $this->userRepository->findByEmail($email);
        if ($user === null || !password_verify($password, $user->hashPassword)) {
            throw new \InvalidArgumentException("Invalid email or password.");
        }

        return $user;
    }

    public function updateName(int $id, string $newName): UserModel
    {
        $user = $this->getUserFromIdOrFail($id);
        if ($user === null) {
            throw new \InvalidArgumentException("User not found.");
        }

        $updateUser = $user->withName($newName);
        return $this->userRepository->save($updateUser);
    }

    public function updateEmail(int $id, string $newEmail): UserModel
    {
        $user = $this->getUserFromIdOrFail($id);

        $existingUser = $this->userRepository->findByEmail($newEmail);
        if ($existingUser !== null && $existingUser->id !== $id) {
            throw new \InvalidArgumentException("Email already in use.");
        }

        $updateUser = $user->withEmail($newEmail);
        return $this->userRepository->save($updateUser);
    }

    public function updatePassword(int $id, string $newPassword): UserModel
    {
        $user = $this->getUserFromIdOrFail($id);

        $hashPassword = password_hash($newPassword, PASSWORD_BCRYPT);
        $updateUser = $user->withPassword($hashPassword);
        return $this->userRepository->save($updateUser);
    }

    private function getUserFromIdOrFail(int $id): UserModel
    {
        $user = $this->userRepository->findById($id);
        if ($user === null) {
            throw new \InvalidArgumentException("User not found.");
        }

        return $user;
    }
}