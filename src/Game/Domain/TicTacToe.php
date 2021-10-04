<?php
declare(strict_types=1);

namespace App\Game\Domain;

use App\Common\AgregateRoot;
use App\Game\Application\Event\SomeoneWonEvent;
use App\Game\Domain\Error\BoxAlreadyBusyException;
use App\Game\Domain\Error\InvalidMovementException;
use App\Game\Domain\Error\InvalidUserException;
use App\Game\Domain\Error\TiedGameException;
use App\User\Domain\User;

class TicTacToe extends AgregateRoot
{
    const X = 'X';
    const O = 'O';
    const VOID = '';

    private User $userOne;

    private User $userTwo;

    private ?Movement $lastMovement = null;

    private GameId $gameId;

    private Board $board;

    public function __construct(GameId $id, User $userOne, User $userTwo)
    {
        $this->userOne = $userOne->sign(self::X);
        $this->userTwo = $userTwo->sign(self::O);

        $this->board = new Board();

        $this->gameId = $id;
    }

    public function field(): array
    {
        return $this->board->field();
    }

    /**
     * @throws BoxAlreadyBusyException
     * @throws TiedGameException|InvalidUserException|InvalidMovementException
     */
    public function play(Movement $movement)
    {
        $movement->assertThatIsTheSameUser($this->lastMovement);
        $this->assertThatIsAnAllowedUser($movement);

        $this->lastMovement = $this->board->drawMovement($movement);

        if($this->board->isThereAWinner($movement)){
            $this->apply(new SomeoneWonEvent($movement->getUser()));
        }

        if($this->board->isThereATie()){
            throw new TiedGameException();
        }
    }

    public function getId(): int
    {
        return $this->gameId->getId();
    }

    private function assertThatIsAnAllowedUser(Movement $movement)
    {
        $user = $movement->getUser();

        if($user->getId() !== $this->userOne->getId() && $user->getId() !== $this->userTwo->getId())
        {
            throw new InvalidUserException();
        }
    }
}
