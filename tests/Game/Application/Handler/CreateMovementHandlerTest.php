<?php
declare(strict_types=1);

namespace Tests\User\Application\Handler;

use App\Game\Application\Command\CreateMovementCommand;
use App\Game\Application\Handler\CreateMovementHandler;
use App\Game\Domain\GameId;
use App\Game\Domain\GameRepository;
use App\Game\Domain\TicTacToe;
use App\Game\Infrastructure\Repository\InMemoryGameRepository;
use App\Game\Domain\GameNotFoundException;
use App\User\Domain\User;
use App\User\Domain\UserNotFoundException;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;
use Tests\TestCase;

class CreateMovementHandlerTest extends PHPUnit_TestCase
{
    const USER_ID_ONE = 0;
    const USER_ID_TWO = 1;
    const GAME_ID = 1;
    /**
     * @var TicTacToe
     */
    private $game;
    /**
     * @var User
     */
    private $userOne;
    /**
     * @var User
     */
    private $userTwo;
    /**
     * @var UserRepository
     */
    private $userRepository;
    /**
     * @var GameRepository
     */
    private $gameRepository;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userOne = User::create(self::USER_ID_ONE);
        $this->userTwo = User::create(self::USER_ID_TWO);
        $gameId = new GameId(self::GAME_ID);

        $this->game = new TicTacToe($gameId, $this->userOne, $this->userTwo);
        $this->userRepository = new InMemoryUserRepository([$this->userOne, $this->userTwo]);
        $this->gameRepository = new InMemoryGameRepository($this->game);
    }

    /** @test */
    public function first()
    {


        $handler = new CreateMovementHandler($this->userRepository, $this->gameRepository, new EventDummyDispatcher());
        $command = new CreateMovementCommand(self::USER_ID_ONE, 1, 1, self::GAME_ID);

        $handler($command);

        $game = $this->gameRepository->findGameById(self::GAME_ID);

        $this->assertEquals([
            ['', '', ''],
            ['', 'X', ''],
            ['', '', '']
        ], $game->field());
    }

    /** @test */
    public function second(){
        $this->expectException(UserNotFoundException::class);

        $handler = new CreateMovementHandler($this->userRepository, $this->gameRepository, new EventDummyDispatcher());
        $command = new CreateMovementCommand(99, 1, 1, self::GAME_ID);

        $handler($command);
    }

    /** @test */
    public function third(){
        $this->expectException(GameNotFoundException::class);

        $handler = new CreateMovementHandler($this->userRepository, $this->gameRepository, new EventDummyDispatcher());
        $command = new CreateMovementCommand(1, 1, 1, 999);

        $handler($command);
    }
}

class EventDummyDispatcher implements EventDispatcherInterface{

    public function dispatch(object $event)
    {
        // TODO: Implement dispatch() method.
    }
}
