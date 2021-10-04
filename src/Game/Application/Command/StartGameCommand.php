<?php

namespace App\Game\Application\Command;

class StartGameCommand
{
    private int $userOneId;

    private int $userTwoId;

    private int $id;

    public function __construct(int $userOneId, int $userTwoId, int $id)
    {
        $this->userOneId = $userOneId;
        $this->userTwoId = $userTwoId;
        $this->id = $id;
    }

    public function getUserOneId(): int
    {
        return $this->userOneId;
    }

    public function getUserTwoId(): int
    {
        return $this->userTwoId;
    }

    public function getId(): int
    {
        return $this->id;
    }


}