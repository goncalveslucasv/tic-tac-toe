<?php

namespace App\Game\Application\Command;

class CreateMovementCommand
{
    /**
     * @var int
     */
    private $userId;
    /**
     * @var int
     */
    private $row;
    /**
     * @var int
     */
    private $column;
    /**
     * @var int
     */
    private $game;

    public function __construct(int $userId, int $row, int $column, int $game)
    {
        $this->userId = $userId;
        $this->row = $row;
        $this->column = $column;
        $this->game = $game;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->userId;
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

    /**
     * @return int
     */
    public function getGame(): int
    {
        return $this->game;
    }


}