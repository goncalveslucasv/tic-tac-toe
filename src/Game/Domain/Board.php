<?php

namespace App\Game\Domain;

use App\Game\Domain\Error\BoxAlreadyBusyException;
use function PHPUnit\Framework\isEmpty;

class Board
{
    private array $field;

    const winningVerticalMovements = [
        [[0,0],[1,0],[2,0]],
        [[0,0],[1,0],[2,0]],
        [[0,0],[1,0],[2,0]]
    ];

    const winningHorizontalMovements = [
        [[0,0],[0,1],[0,2]],
        [[1,0],[1,1],[1,2]],
        [[2,0],[2,1],[2,2]],
    ];

    const winningDiagonalMovements = [
        [[0,0],[1,1],[2,2]],
        [[0,2],[1,1],[2,0]]
    ];

    public function __construct()
    {
        $this->field = [
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
        ];
    }

    public function field(): array
    {
        return $this->field;
    }

    /**
     * @throws BoxAlreadyBusyException
     */
    public function drawMovement(Movement $movement)
    {
        if($this->field[$movement->getColumn()][$movement->getRow()] !== TicTacToe::VOID){
            throw new BoxAlreadyBusyException();
        }

        $this->field[$movement->getColumn()][$movement->getRow()] = $movement->getUserSign();
    }

    public function getSign(Movement $movement)
    {
        return $this->field[$movement->getColumn()][$movement->getRow()];
    }

    public function isThereATie(): bool
    {
        $emptyBox = [];
        array_walk($this->field, function (array $row) use (&$emptyBox){
            array_walk($row, function ($column) use (&$emptyBox){
                if(!$column){
                    array_push($emptyBox, 1);
                }
            });
        });

        return count($emptyBox) === 0;
    }

    public function isThereAWinner(Movement $movement)
    {
        $sign = $movement->getUserSign();

        $winnerMovements = [
            ...self::winningVerticalMovements,
            ...self::winningHorizontalMovements,
            ...self::winningDiagonalMovements
        ];

        array_walk($winnerMovements, function($field) use ($sign, &$isWinner){
            if($this->field[$field[0][0]][$field[0][1]] === $sign &&
                $this->field[$field[1][0]][$field[1][1]] === $sign &&
                $this->field[$field[2][0]][$field[2][1]] === $sign)
            {
                $isWinner = true;
            }
        });

        return $isWinner;
    }
}