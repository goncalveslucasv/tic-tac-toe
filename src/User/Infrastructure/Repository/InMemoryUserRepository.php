<?php
declare(strict_types=1);

namespace App\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;

class InMemoryUserRepository implements UserRepository
{
    /**
     * @var User[]
     */
    private array $users;

    public function __construct(array $users = null)
    {
        $this->users = $users ?? [
            0 => new User(new UserId(0)),
            1 => new User(new UserId(1)),
            2 => new User(new UserId(2)),
            3 => new User(new UserId(3)),
            4 => new User(new UserId(4)),
            5 => new User(new UserId(5)),
        ];
    }

    public function findAll(): array
    {
        return array_values($this->users);
    }

    public function findUserById(int $id): ?User
    {
        if (!isset($this->users[$id])) {
            return null;
        }

        return $this->users[$id];
    }

    public function save(User $user): User
    {
        array_push($this->users, $user);
        return $user;
    }

    public function remove(User $userToDelete)
    {
        $this->users = array_filter($this->users, function(User $user) use ($userToDelete){
            return $user->getId() !== $userToDelete->getId();
        });
    }
}
