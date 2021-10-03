<?php

namespace App\Game\Application\Handler;

use App\Game\Application\Command\CreateEvent;
use App\Game\Application\Command\StartGameCommand;
use App\Game\Domain\GameId;
use App\Game\Domain\GameRepository;
use App\Game\Domain\TicTacToe;
use App\User\Domain\UserNotFoundException;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class StartGameHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var GameRepository
     */
    private $gameRepository;

    public function __construct(UserRepository $userRepository, GameRepository $gameRepository)
    {
        $this->userRepository = $userRepository;
        $this->gameRepository = $gameRepository;
    }

    public function __invoke(StartGameCommand $message)
    {
        $userOne = $this->userRepository->findUserById($message->getUserOneId());
        $userTwo = $this->userRepository->findUserById($message->getUserTwoId());

        if(empty($userOne) || empty($userTwo)){
            throw new UserNotFoundException();
        }

        $gameId = new GameId($message->getId());
        $game = new TicTacToe($gameId, $userOne, $userTwo);

        $this->gameRepository->save($game);

        return $game;
    }
}