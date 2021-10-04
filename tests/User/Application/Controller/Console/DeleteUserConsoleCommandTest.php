<?php
declare(strict_types=1);

namespace App\Tests\User\Application\Controller\Console;

use App\User\Application\Controller\Console\DeleteUserConsoleCommand;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;

class DeleteUserConsoleCommandTest extends KernelTestCase
{

    private $stubContainer;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->stubContainer = static::getContainer();
    }

    /** @test */
    public function it_should_delete_a_user_when_a_valid_user_is_passed()
    {
        /** @var DeleteUserConsoleCommand $command */
        $command = $this->stubContainer->get(DeleteUserConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => 4
        ]);

        $this->assertEquals('User deleted ID:4', trim($commandTester->getDisplay()));
    }


    /** @test */
    public function it_should_not_delete_a_user_when_an_invalid_user_is_passed()
    {
        $this->expectException(Exception::class);
        /** @var DeleteUserConsoleCommand $command */
        $command = $this->stubContainer->get(DeleteUserConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => 9
        ]);

    }
}
