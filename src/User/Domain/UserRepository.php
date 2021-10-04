<?php
declare(strict_types=1);

namespace App\User\Domain;

interface UserRepository
{
    /**
     * @return User[]
     */
    public function findAll(): array;

    public function findUserById(int $id): ?User;

    public function save(User $user): User;

    public function remove(User $user);
}
