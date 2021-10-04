<?php
declare(strict_types=1);

namespace App\Game\Domain;

use App\Common\AgregateRoot;
use App\Game\Application\Event\SomeoneWonEvent;
use App\User\Domain\User;

class TicTacToe extends AgregateRoot
{
    const X = 'X';
    const O = 'O';

    /**
     * @var User
     */
    private $userOne;
    /**
     * @var User
     */
    private $userTwo;

    /**
     * @var Movement
     */
    private $lastMovement;
    /**
     * @var GameId
     */
    private $gameId;

    public function __construct(GameId $id, User $userOne, User $userTwo)
    {
        $this->userOne = $userOne->sign(self::X);
        $this->userTwo = $userTwo->sign(self::O);

        $this->board = new Board();

        $this->gameId = $id;
    }

    public function field()
    {
        return $this->board->field();
    }

    /**
     * @throws BoxAlreadyBusyException
     * @throws InvalidTurnException|TiedGameException
     */
    public function play(Movement $movement)
    {
        if (isset($this->lastMovement)) {
            $this->assertThatTheBoxIsFree($movement);
            $this->assertThatIsADifferentPlayer($movement);
        }
        $this->assertThatIsAnAllowedPlayer($movement);

        $this->lastMovement = $movement;
        $this->board->drawMovement($movement);

        if($this->board->isThereAWinner($movement)){
            $this->apply(new SomeoneWonEvent($movement->getUser()));
        }

        if($this->board->isThereATie()){
            throw new TiedGameException();
        }
    }

    private function assertThatTheBoxIsFree(Movement $movement)
    {
        $box = $this->board->getSign($movement);

        if($box === self::X || $box === self::O){
            throw new BoxAlreadyBusyException();
        }
    }

    private function assertThatIsADifferentPlayer(Movement $movement){
        $this->lastMovement->assertThatIsADifferentPlayer($movement);
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->gameId->getId();
    }

    private function assertThatIsAnAllowedPlayer(Movement $movement)
    {
        $user = $movement->getUser();

        if($user->getId() !== $this->userOne->getId() && $user->getId() !== $this->userTwo->getId())
        {
            throw new InvalidUserException();
        }

    }
}
