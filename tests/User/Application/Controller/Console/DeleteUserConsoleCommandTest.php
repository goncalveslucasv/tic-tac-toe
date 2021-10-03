<?php
declare(strict_types=1);

namespace Tests\User\Application\Controller\Console;

use App\User\Application\Controller\Console\CreateUserConsoleCommand;
use App\User\Application\Controller\Console\DeleteUserConsoleCommand;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

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
    public function first()
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
    public function second()
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
