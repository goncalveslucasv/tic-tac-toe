<?php
declare(strict_types=1);

namespace App\Tests\Game\Application\Handler;

use App\Game\Application\Command\StartGameCommand;
use App\Game\Application\Handler\StartGameHandler;
use App\Game\Domain\GameId;
use App\Game\Domain\TicTacToe;
use App\Game\Infrastructure\Repository\InMemoryGameRepository;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Domain\Error\UserNotFoundException;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class StartGameHandlerTest extends PHPUnit_TestCase
{
    const USER_ID_ONE = 1;
    const USER_ID_TWO = 2;
    const NON_EXIST_USER = 999;
    const GAME_ID = 1;

    private StartGameHandler $handler;
    private InMemoryGameRepository $gameRepository;

    protected function setUp(): void
    {
        $userRepository = new InMemoryUserRepository();
        $this->gameRepository = new InMemoryGameRepository();
        $this->handler = new StartGameHandler($userRepository, $this->gameRepository);
    }

    /** @test */
    public function it_should_create_a_game_with_right_users()
    {
        $command = new StartGameCommand(self::USER_ID_ONE, self::USER_ID_TWO, self::GAME_ID);

        ($this->handler)($command);

        $newGame = $this->gameRepository->findGameById(1);
        $expectedGame = new TicTacToe(new GameId(self::GAME_ID), new User(new UserId(self::USER_ID_ONE)), new User(new UserId(self::USER_ID_TWO)));
        $this->assertEquals($expectedGame, $newGame);
    }

    /** @test */
    public function it_should_not_create_a_game_if_an_user_is_wrong()
    {
        $this->expectException(UserNotFoundException::class);

        $command = new StartGameCommand(self::NON_EXIST_USER, self::USER_ID_TWO, self::GAME_ID);

        ($this->handler)($command);
    }
}
