<?php
declare(strict_types=1);

namespace App\Tests\User\Application\Controller\Console;

use App\User\Application\Controller\Console\CreateUserConsoleCommand;
use App\User\Domain\Error\UserAlreadyExistsException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class CreateUserConsoleCommandTest extends KernelTestCase
{

    private $stubContainer;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->stubContainer = static::getContainer();
    }

    /** @test */
    public function it_should_create_a_user_from_command_line_with_a_right_id()
    {
        /** @var CreateUserConsoleCommand $command */
        $command = $this->stubContainer->get(CreateUserConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => 6
        ]);

        $this->assertEquals('User created ID:6', trim($commandTester->getDisplay()));
    }


    /** @test */
    public function it_should_not_create_a_user_when_the_user_already_exists()
    {
        $this->expectException(UserAlreadyExistsException::class);
        /** @var CreateUserConsoleCommand $command */
        $command = $this->stubContainer->get(CreateUserConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => 4
        ]);

    }
}
