<?php

namespace App\Game\Application\Listener;

use App\Game\Application\Event\SomeoneWonEvent;
use App\Game\Domain\GameOver;

class WinnerListener
{
    /**
     * @throws GameOver
     */
    public function onGameWinner(SomeoneWonEvent $event): void
    {
        $user = $event->getUser();

        throw new GameOver('The winner was the player number:'.$user->getId());
    }
}