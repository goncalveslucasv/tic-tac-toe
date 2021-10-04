<?php
declare(strict_types=1);

namespace App\Tests\User\Application\Handler;

use App\User\Application\Command\DeleteUserCommand;
use App\User\Application\Handler\DeleteUserHandler;
use App\User\Domain\UserNotFoundException;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class DeleteUserHandlerTest extends PHPUnit_TestCase
{

    /** @test */
    public function it_should_delete_a_user_with_a_delete_user_command()
    {
        $repository = new InMemoryUserRepository();
        $handler = new DeleteUserHandler($repository);
        $command = new DeleteUserCommand(5);

        $handler($command);

        $this->assertNull($repository->findUserById(5));
    }

    /** @test */
    public function it_should_not_delete_a_user_with_a_invalid_deleter_user_command()
    {
        $this->expectException(UserNotFoundException::class);

        $repository = new InMemoryUserRepository();
        $handler = new DeleteUserHandler($repository);
        $command = new DeleteUserCommand(6);

        $handler($command);
    }
}
