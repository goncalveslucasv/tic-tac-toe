<?php
declare(strict_types=1);

namespace Tests\User\Application\Handler;

use App\User\Application\Handler\FindUserByIdHandler;
use App\User\Application\Query\FindUserByIdQuery;
use App\User\Domain\User;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase;

class FindUserByIdHandlerTest extends PHPUnit_TestCase
{

    /** @test */
    public function first()
    {
        $repository = new InMemoryUserRepository();
        $handler = new FindUserByIdHandler($repository);
        $command = new FindUserByIdQuery(5);

        $user = $handler($command);

        $this->assertEquals(new User(5), $user);
    }
}
