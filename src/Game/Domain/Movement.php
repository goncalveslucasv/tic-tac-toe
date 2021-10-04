<?php
declare(strict_types=1);

namespace App\Game\Domain;

use App\User\Domain\User;

class Movement
{
    private User $user;

    private int $row;

    private int $column;

    public function __construct(User $user, int $row, int $column)
    {
        $this->user = $user;
        $this->row = $row;
        $this->column = $column;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function getUserSign(): string
    {
        return $this->user->getSign();
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function __toString()
    {
        return $this->getUserSign();
    }

    public function assertThatIsADifferentPlayer(Movement $movement)
    {
        if($this->user === $movement->getUser()){
            throw new InvalidTurnException();
        }
    }

}
