<?php

namespace App\Game\Application\Command;

class StartGameCommand
{
    /**
     * @var int
     */
    private $userOneId;
    /**
     * @var int
     */
    private $userTwoId;
    /**
     * @var int
     */
    private $id;

    public function __construct(int $userOneId, int $userTwoId, int $id)
    {
        $this->userOneId = $userOneId;
        $this->userTwoId = $userTwoId;
        $this->id = $id;
    }

    /**
     * @return int
     */
    public function getUserOneId(): int
    {
        return $this->userOneId;
    }

    /**
     * @return int
     */
    public function getUserTwoId(): int
    {
        return $this->userTwoId;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }


}