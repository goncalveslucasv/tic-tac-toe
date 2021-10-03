<?php
declare(strict_types=1);

namespace App\Game\Domain;

interface GameRepository
{
    public function findGameById(int $id): ?TicTacToe;

    public function save(TicTacToe $user): TicTacToe;
}
