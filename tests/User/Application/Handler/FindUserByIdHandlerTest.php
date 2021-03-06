<?php
declare(strict_types=1);

namespace App\Tests\User\Application\Handler;

use App\User\Application\Handler\FindUserByIdHandler;
use App\User\Application\Query\FindUserByIdQuery;
use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class FindUserByIdHandlerTest extends PHPUnit_TestCase
{

    /** @test */
    public function it_should_return_a_user_when_a_query_is_passed()
    {
        $repository = new InMemoryUserRepository();
        $handler = new FindUserByIdHandler($repository);
        $command = new FindUserByIdQuery(5);

        $user = $handler($command);

        $this->assertEquals(new User(new UserId(5)), $user);
    }
}
