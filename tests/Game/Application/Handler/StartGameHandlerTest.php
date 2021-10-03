<?php
declare(strict_types=1);

namespace Tests\User\Application\Handler;

use App\Game\Application\Command\StartGameCommand;
use App\Game\Application\Handler\StartGameHandler;
use App\Game\Domain\GameId;
use App\Game\Domain\TicTacToe;
use App\Game\Infrastructure\Repository\InMemoryGameRepository;
use App\User\Domain\User;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase;

class StartGameHandlerTest extends PHPUnit_TestCase
{
    const USER_ID_ONE = 1;
    const USER_ID_TWO = 2;
    const GAME_ID = 1;

    /** @test */
    public function first()
    {
        $userRepository = new InMemoryUserRepository();
        $gameRepository = new InMemoryGameRepository();

        $handler = new StartGameHandler($userRepository, $gameRepository);
        $command = new StartGameCommand(self::USER_ID_ONE, self::USER_ID_TWO, self::GAME_ID);

        $handler($command);

        $this->assertEquals(new TicTacToe(new GameId(self::GAME_ID), new User(self::USER_ID_ONE), new User(self::USER_ID_TWO)),$gameRepository->findGameById(1));
    }
}
