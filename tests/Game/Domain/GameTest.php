<?php
declare(strict_types=1);

namespace Tests\Domain\Game;

use App\Game\Domain\BoxAlreadyBusyException;
use App\Game\Domain\GameId;
use App\Game\Domain\InvalidTurnException;
use App\Game\Domain\InvalidUserException;
use App\Game\Domain\Movement;
use App\Game\Domain\TicTacToe;
use App\Game\Domain\TiedGameException;
use App\User\Domain\User;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase;

class GameTest extends PHPUnit_TestCase
{
    private $game;
    private $userOne;
    private $userTwo;

    protected function setUp(): void
    {
        $this->userOne = User::create(1);
        $this->userTwo = User::create(2);
        $gameId = new GameId(1);

        $this->game = new TicTacToe($gameId, $this->userOne, $this->userTwo);
    }

    /** @test */
    public function first()
    {
        $field = $this->game->field();

        $expectedFields = [
            ['', '', ''],
            ['', '', ''],
            ['', '', '']
        ];

        $this->assertEquals($field, $expectedFields);
    }

    /** @test */
    public function second()
    {
        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $fields = $this->game->field();
        $expectedFields = [
            ['X', '', ''],
            ['', '', ''],
            ['', '', '']
        ];


        $this->assertEquals($expectedFields, $fields);
    }

    /** @test */
    public function third()
    {
        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 1, 1);
        $this->game->play($movement);

        $fields = $this->game->field();
        $expectedFields = [
            ['X', '', ''],
            ['', 'O', ''],
            ['', '', '']
        ];


        $this->assertEquals($expectedFields, $fields);
    }

    /** @test */
    public function four()
    {
        $this->expectException(BoxAlreadyBusyException::class);

        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 0, 0);
        $this->game->play($movement);
    }

    /** @test */
    public function five()
    {
        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 1, 1);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 1, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 1, 2);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 2, 0);
        $this->game->play($movement);


        $fields = $this->game->field();
        $expectedFields = [
            ['X', 'X', 'X'],
            ['', 'O', ''],
            ['', 'O', '']
        ];


        $this->assertEquals($expectedFields, $fields);
        $this->assertEquals($this->userOne, $this->game->events()[0]->getUser());
    }

    /** @test */
    public function six()
    {
        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 0, 1);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 1, 1);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 1, 2);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 2, 2);
        $this->game->play($movement);


        $fields = $this->game->field();
        $expectedFields = [
            ['X', '', ''],
            ['O', 'X', ''],
            ['', 'O', 'X']
        ];

        $this->assertEquals($expectedFields, $fields);
        $this->assertEquals($this->userOne, $this->game->events()[0]->getUser());
    }

    /** @test */
    public function seven()
    {
        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 1, 1);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 0, 1);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 2, 2);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 0, 2);
        $this->game->play($movement);


        $fields = $this->game->field();
        $expectedFields = [
            ['X', '', ''],
            ['X', 'O', ''],
            ['X', '', 'O']
        ];

        $this->assertEquals($expectedFields, $fields);
        $this->assertEquals($this->userOne, $this->game->events()[0]->getUser());
    }

    /** @test */
    public function eight(){
        $this->expectException(InvalidTurnException::class);
        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 0, 1);
        $this->game->play($movement);
    }


    /** @test */
    public function nine(){
        $this->expectException(TiedGameException::class);

        $this->game->play(new Movement($this->userOne, 0, 0));
        $this->game->play(new Movement($this->userTwo, 0, 1));
        $this->game->play(new Movement($this->userOne, 0, 2));
        $this->game->play(new Movement($this->userTwo, 1, 1));
        $this->game->play(new Movement($this->userOne, 1, 0));
        $this->game->play(new Movement($this->userTwo, 1, 2));
        $this->game->play(new Movement($this->userOne, 2, 1));
        $this->game->play(new Movement($this->userTwo, 2, 0));
        $this->game->play(new Movement($this->userOne, 2, 2));


        $fields = $this->game->field();
        $expectedFields = [
            ['X', 'X', 'O'],
            ['O', 'O', 'X'],
            ['X', 'O', 'X']
        ];

        $this->assertEquals($expectedFields, $fields);
    }

    /** @test */
    public function ten(){
        $this->expectException(InvalidUserException::class);

        $this->game->play(new Movement(new User(99), 0, 0));
    }

}
