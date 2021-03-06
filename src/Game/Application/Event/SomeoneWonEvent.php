<?php

namespace App\Game\Application\Event;

use App\User\Domain\User;
use Symfony\Contracts\EventDispatcher\Event;

class SomeoneWonEvent extends Event
{
    public const NAME = 'game.winner';

    protected User $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getUser(): User
    {
        return $this->user;
    }


}