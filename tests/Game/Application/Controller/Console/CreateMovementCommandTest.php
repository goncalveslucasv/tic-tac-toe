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
use App\User\Domain\UserId;
use App\User\Domain\UserRepository;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CreateMovementCommandTest extends KernelTestCase
{

    private ContainerInterface $stubContainer;
    private User $userOne;
    private User $userTwo;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->stubContainer = static::getContainer();
        $this->userOne = (new User(new UserId(0)))->sign(TicTacToe::X);
        $this->userTwo = (new User(new UserId(1)))->sign(TicTacToe::O);
        $this->stubContainer->set(UserRepository::class, new InMemoryUserRepository([$this->userOne, $this->userTwo]));
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

        $game = new TicTacToe(new GameId(1), $this->userOne, $this->userTwo);

        $game->play(new Movement($this->userOne, 0, 0));
        $game->play(new Movement($this->userTwo, 1, 1));
        $game->play(new Movement($this->userOne, 1, 0));
        $game->play( new Movement($this->userTwo, 1, 2));
        $repo =  new InMemoryGameRepository($game);

        $this->stubContainer->set(GameRepository::class, $repo);

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

        $game = new TicTacToe(new GameId(1), $this->userOne, $this->userTwo);

        $game->play(new Movement($this->userOne, 0, 0));
        $game->play(new Movement($this->userTwo, 0, 1));
        $game->play(new Movement($this->userOne, 0, 2));
        $game->play(new Movement($this->userTwo, 1, 1));
        $game->play(new Movement($this->userOne, 1, 0));
        $game->play(new Movement($this->userTwo, 1, 2));
        $game->play(new Movement($this->userOne, 2, 1));
        $game->play(new Movement($this->userTwo, 2, 0));

        $gameRepository =  new InMemoryGameRepository($game);
        $this->stubContainer->set(GameRepository::class, $gameRepository);

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
