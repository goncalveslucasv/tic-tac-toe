<?php
declare(strict_types=1);

namespace App\Game\Domain;

use App\User\Domain\User;

class Movement
{
    /**
     * @var User
     */
    private $user;
    /**
     * @var int
     */
    private $row;
    /**
     * @var int
     */
    private $column;

    public function __construct(User $user, int $row, int $column)
    {
        $this->user = $user;
        $this->row = $row;
        $this->column = $column;
    }

    /**
     * @return int
     */
    public function getRow(): int
    {
        return $this->row;
    }

    /**
     * @return int
     */
    public function getColumn(): int
    {
        return $this->column;
    }

    public function getUserSign()
    {
        return $this->user->getSign();
    }

    public function getUser()
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
