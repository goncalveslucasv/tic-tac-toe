<?php
declare(strict_types=1);

namespace Tests\User\Infrastructure;

use App\Game\Domain\GameId;
use App\Game\Domain\TicTacToe;
use App\Game\Infrastructure\Repository\InMemoryGameRepository;
use App\User\Domain\User;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase;

class InMemoryGameRepositoryTest extends PHPUnit_TestCase
{
    const USER_ID_ONE = 1;
    const USER_ID_TWO = 2;
    const GAME_ID = 1;

    /** @test */
    public function fist()
    {
        $repository = new InMemoryGameRepository(new TicTacToe(new GameId(self::GAME_ID), new User(self::USER_ID_ONE), new User(self::USER_ID_TWO)));

        $game = $repository->findGameById(1);

        $this->assertEquals([
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
        ], $game->field());
    }
    /** @test */
    public function second()
    {
        $repository = new InMemoryGameRepository();

        $game = new TicTacToe(new GameId(self::GAME_ID), new User(self::USER_ID_ONE), new User(self::USER_ID_TWO));

        $repository->save($game);
        $findedGame = $repository->findGameById(1);

        $this->assertEquals($game, $findedGame);
    }



}
