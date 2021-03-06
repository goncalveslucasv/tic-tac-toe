<?php
declare(strict_types=1);

namespace App\Game\Infrastructure\Repository;


use App\Game\Domain\GameId;
use App\Game\Domain\GameRepository;
use App\Game\Domain\TicTacToe;
use App\User\Domain\User;
use App\User\Domain\UserId;

class InMemoryGameRepository implements GameRepository
{
    private TicTacToe $game;

    public function __construct(?TicTacToe $game = null)
    {
        $gameNew = new TicTacToe(new GameId(1), new User(new UserId(1)), new User( new UserId(2)));
        $this->game = $game ?? $gameNew;
    }

    public function save(TicTacToe $ticTacToe): TicTacToe
    {
        $this->game = $ticTacToe;
        return $ticTacToe;
    }


    public function findGameById(int $id): ?TicTacToe
    {
         if($this->game->getId() === $id)
         {
             return $this->game;
         }

         return null;
    }
}
