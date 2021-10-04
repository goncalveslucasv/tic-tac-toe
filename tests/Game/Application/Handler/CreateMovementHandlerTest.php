<?php
declare(strict_types=1);

namespace App\Tests\Game\Application\Handler;

use App\Game\Application\Command\CreateMovementCommand;
use App\Game\Application\Handler\CreateMovementHandler;
use App\Game\Domain\GameId;
use App\Game\Domain\GameRepository;
use App\Game\Domain\TicTacToe;
use App\Game\Infrastructure\Repository\InMemoryGameRepository;
use App\Game\Domain\Error\GameNotFoundException;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\Error\UserNotFoundException;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Psr\EventDispatcher\EventDispatcherInterface;

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
    /**
     * @var mixed|\PHPUnit\Framework\MockObject\Stub|EventDispatcherInterface
     */
    private $stubEventDispatcher;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userOne = new User(new UserId(self::USER_ID_ONE));
        $this->userTwo = new User(new UserId(self::USER_ID_TWO));
        $gameId = new GameId(self::GAME_ID);

        $this->game = new TicTacToe($gameId, $this->userOne, $this->userTwo);
        $this->userRepository = new InMemoryUserRepository([$this->userOne, $this->userTwo]);
        $this->gameRepository = new InMemoryGameRepository($this->game);
        $this->stubEventDispatcher = $this->createStub(EventDispatcherInterface::class);

    }

    /** @test */
    public function it_should_create_a_movement_from_create_movement_command()
    {
        $handler = new CreateMovementHandler($this->userRepository, $this->gameRepository, $this->stubEventDispatcher);
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
    public function it_should_not_create_a_movement_if_user_is_wrong(){
        $this->expectException(UserNotFoundException::class);

        $handler = new CreateMovementHandler($this->userRepository, $this->gameRepository, $this->stubEventDispatcher);
        $command = new CreateMovementCommand(99, 1, 1, self::GAME_ID);

        $handler($command);
    }

    /** @test */
    public function it_should_not_create_a_movement_in_a_non_existent_game(){
        $this->expectException(GameNotFoundException::class);

        $handler = new CreateMovementHandler($this->userRepository, $this->gameRepository, $this->stubEventDispatcher);
        $command = new CreateMovementCommand(1, 1, 1, 999);

        $handler($command);
    }
}
