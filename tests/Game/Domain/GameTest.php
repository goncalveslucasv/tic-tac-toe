<?php
declare(strict_types=1);

namespace App\Tests\Game\Domain;

use App\Game\Domain\Error\BoxAlreadyBusyException;
use App\Game\Domain\GameId;
use App\Game\Domain\Error\InvalidMovementException;
use App\Game\Domain\Error\InvalidUserException;
use App\Game\Domain\Movement;
use App\Game\Domain\TicTacToe;
use App\Game\Domain\Error\TiedGameException;
use App\User\Domain\User;
use App\User\Domain\UserId;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class GameTest extends PHPUnit_TestCase
{
    private $game;
    private $userOne;
    private $userTwo;

    protected function setUp(): void
    {
        $this->userOne = new User(new UserId(1));
        $this->userTwo = new User(new UserId(2));
        $gameId = new GameId(1);

        $this->game = new TicTacToe($gameId, $this->userOne, $this->userTwo);
    }

    /** @test */
    public function it_should_have_an_empty_board()
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
    public function it_should_allow_a_new_movement_in_a_new_game()
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
    public function it_should_allow_two_users_play()
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
    public function it_should_not_allow_two_marks_in_the_same_box()
    {
        $this->expectException(BoxAlreadyBusyException::class);

        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userTwo, 0, 0);
        $this->game->play($movement);
    }

    /** @test */
    public function it_should_win_a_user_with_a_horizontal_winner_mark()
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
    public function it_should_win_a_user_with_a_diagonal_winner_mark()
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
    public function it_should_win_a_user_with_a_vertical_winner_mark()
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
    public function it_should_not_a_user_play_twice(){
        $this->expectException(InvalidMovementException::class);
        $movement = new Movement($this->userOne, 0, 0);
        $this->game->play($movement);

        $movement = new Movement($this->userOne, 0, 1);
        $this->game->play($movement);
    }


    /** @test */
    public function it_should_throw_a_error_when_the_game_ends_without_a_winner(){
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
    public function it_should_not_create_a_movement_a_user_not_invited_to_the_game(){
        $this->expectException(InvalidUserException::class);

        $this->game->play(new Movement(new User(new UserId(99)), 0, 0));
    }

}
