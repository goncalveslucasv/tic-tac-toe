<?php

namespace App\Game\Application\Handler;

use App\Game\Application\Command\CreateMovementCommand;
use App\Game\Domain\BoxAlreadyBusyException;
use App\Game\Domain\GameNotFoundException;
use App\Game\Domain\GameRepository;
use App\Game\Domain\InvalidUserException;
use App\Game\Domain\Movement;
use App\Game\Domain\TicTacToe;
use App\Game\Domain\TiedGameException;
use App\User\Domain\UserNotFoundException;
use App\User\Domain\UserRepository;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateMovementHandler implements MessageHandlerInterface
{

    private UserRepository $userRepository;

    private GameRepository $gameRepository;

    private EventDispatcherInterface $eventDispatcher;

    public function __construct(UserRepository $userRepository, GameRepository $gameRepository, EventDispatcherInterface $eventDispatcher)
    {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
        $this->eventDispatcher = $eventDispatcher;
    }

    /**
     * @throws GameNotFoundException
     * @throws UserNotFoundException
     * @throws InvalidUserException
     * @throws BoxAlreadyBusyException
     * @throws TiedGameException
     */
    public function __invoke(CreateMovementCommand $message): TicTacToe
    {
        $user = $this->userRepository->findUserById($message->getUserId());
        $game = $this->gameRepository->findGameById($message->getGame());

        if(empty($user)){
            throw new UserNotFoundException();
        }

        if(empty($game)) throw new GameNotFoundException();

        $movement = new Movement($user, $message->getRow(), $message->getColumn());

        $game->play($movement);

        $game->events() && $this->eventDispatcher->dispatch(...$game->events());

        return $this->gameRepository->save($game);
    }
}