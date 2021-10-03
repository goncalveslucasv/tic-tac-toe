<?php
declare(strict_types=1);

namespace Tests\User\Application\Handler;

use App\User\Application\Command\CreateUserCommand;
use App\User\Application\Handler\CreateUserHandler;
use App\User\Domain\User;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase;

class CreateUserHandlerTest extends PHPUnit_TestCase
{

    /** @test */
    public function first()
    {
        $repository = new InMemoryUserRepository();
        $handler = new CreateUserHandler($repository);
        $command = new CreateUserCommand(6);

        $user = $handler($command);

        $this->assertEquals(new User(6), $repository->findUserById(6));
        $this->assertEquals(new User(6), $user);
    }
}
