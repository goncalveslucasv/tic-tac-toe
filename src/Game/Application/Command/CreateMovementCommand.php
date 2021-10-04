<?php

namespace App\Game\Application\Command;

class CreateMovementCommand
{
    private int $userId;

    private int $row;

    private int $column;

    private int $game;

    public function __construct(int $userId, int $row, int $column, int $game)
    {
        $this->userId = $userId;
        $this->row = $row;
        $this->column = $column;
        $this->game = $game;
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    public function getRow(): int
    {
        return $this->row;
    }

    public function getColumn(): int
    {
        return $this->column;
    }

    public function getGame(): int
    {
        return $this->game;
    }


}