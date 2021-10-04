<?php
declare(strict_types=1);

namespace App\Tests\Game\Application\Controller\Console;

use App\Game\Application\Controller\Console\StartGameConsoleCommand;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class StartGameConsoleCommandTest extends KernelTestCase
{
    const VALID_USERS = [
        'userOneId' => 1,
        'userTwoId' => 2
    ];
    const INVALID_USERS = [
        'userOneId' => 0000,
        'userTwoId' => 9999
    ];

    private $stubContainer;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->stubContainer = static::getContainer();
    }

    /** @test */
    public function it_should_create_a_new_game_with_right_users()
    {
        /** @var StartGameConsoleCommand $command */
        $command = $this->stubContainer->get(StartGameConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute(self::VALID_USERS);

        $this->assertEquals('Game was created', trim($commandTester->getDisplay()));
    }

    /** @test */
    public function it_should_not_create_a_new_game_with_invalid_users()
    {
        /** @var StartGameConsoleCommand $command */
        $command = $this->stubContainer->get(StartGameConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute(self::INVALID_USERS);


        $this->assertStringContainsString("The user you requested does not exist", trim($commandTester->getDisplay()));
    }

}
