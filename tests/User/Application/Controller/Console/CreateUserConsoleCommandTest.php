<?php
declare(strict_types=1);

namespace Tests\User\Application\Controller\Console;

use App\User\Application\Controller\Console\CreateUserConsoleCommand;
use Exception;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Console\Tester\CommandTester;
use Tests\TestCase;

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
    public function first()
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
    public function second()
    {
        $this->expectException(Exception::class);
        /** @var CreateUserConsoleCommand $command */
        $command = $this->stubContainer->get(CreateUserConsoleCommand::class);

        $commandTester = new CommandTester($command);
        $commandTester->execute([
            'userId' => 4
        ]);

    }
}
