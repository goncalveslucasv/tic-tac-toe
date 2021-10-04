<?php
declare(strict_types=1);

namespace App\Tests\Game\Application\Controller\Console;

use App\Game\Application\Controller\Console\CreateMovementConsoleCommand;
use App\Game\Domain\GameId;
use App\Game\Domain\GameRepository;
use App\Game\Domain\Movement;
use App\Game\Domain\TicTacToe;
use App\Game\Infrastructure\Repository\InMemoryGameRepository;
use App\User\Domain\User;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateMovementCommandTest extends KernelTestCase
{

    private $stubContainer;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->stubContainer = static::getContainer();
    }

    /** @test */
    public function it_should_create_a_movement_from_a_line_command_interface()
    {
        /** @var CreateMovementConsoleCommand $command */
        $command = $this->stubContainer->get(CreateMovementConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "userId" => 1,
            "row" => 2,
            "column" => 0,
            "gameId" => 1
        ]);

        $this->assertEquals('Movement done', trim($commandTester->getDisplay()));
    }


    /** @test */
    public function it_should_do_the_last_movement_before_winning()
    {
        /** @var CreateMovementConsoleCommand $command */
        $command = $this->stubContainer->get(CreateMovementConsoleCommand::class);

        $userOne = new User(0);
        $userTwo = new User(1);
        $game = new TicTacToe(new GameId(1), $userOne, $userTwo);

        $game->play(new Movement($userOne, 0, 0));
        $game->play(new Movement($userTwo, 1, 1));
        $game->play(new Movement($userOne, 1, 0));
        $game->play( new Movement($userTwo, 1, 2));
        $repo =  new InMemoryGameRepository($game);

        $this->stubContainer->set(GameRepository::class, $repo);
        $this->stubContainer->set(UserRepository::class, new InMemoryUserRepository([$userOne, $userTwo]));

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "userId" => 0,
            "row" => 2,
            "column" => 0,
            "gameId" => 1
        ]);

        $this->assertStringContainsString('The winner was the player number:0', trim($commandTester->getDisplay()));
    }



    /** @test */
    public function it_should_do_the_last_movement_before_tying()
    {
        /** @var CreateMovementConsoleCommand $command */
        $command = $this->stubContainer->get(CreateMovementConsoleCommand::class);

        $userOne = new User(0);
        $userTwo = new User(1);
        $game = new TicTacToe(new GameId(1), $userOne, $userTwo);

        $game->play(new Movement($userOne, 0, 0));
        $game->play(new Movement($userTwo, 0, 1));
        $game->play(new Movement($userOne, 0, 2));
        $game->play(new Movement($userTwo, 1, 1));
        $game->play(new Movement($userOne, 1, 0));
        $game->play(new Movement($userTwo, 1, 2));
        $game->play(new Movement($userOne, 2, 1));
        $game->play(new Movement($userTwo, 2, 0));
        $repo =  new InMemoryGameRepository($game);

        $this->stubContainer->set(GameRepository::class, $repo);
        $this->stubContainer->set(UserRepository::class, new InMemoryUserRepository([$userOne, $userTwo]));

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            "userId" => 0,
            "row" => 2,
            "column" => 2,
            "gameId" => 1
        ]);

        $this->assertStringContainsString('The game ended tied', trim($commandTester->getDisplay()));
    }

}
