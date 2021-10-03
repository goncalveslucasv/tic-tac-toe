<?php
declare(strict_types=1);

namespace Tests\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;
use Tests\TestCase;

class InMemoryUserRepositoryTest extends PHPUnit_TestCase
{
    /** @test */
    public function fist()
    {
        $repository = new InMemoryUserRepository();

        $users = $repository->findAll();

        $this->assertEquals([
            new User(0),
            new User(1),
            new User(2),
            new User(3),
            new User(4),
            new User(5)
        ], $users);
    }

    /** @test */
    public function second()
    {
        $repository = new InMemoryUserRepository();

        $result = $repository->findUserById(2);

        $this->assertEquals(new User(2), $result);
    }

    /** @test */
    public function three()
    {
        $repository = new InMemoryUserRepository();

        $result = $repository->findUserById(7);

        $this->assertNull($result);
    }

    /** @test */
    public function four()
    {
        $aNewUser = new User(6);
        $repository = new InMemoryUserRepository();

        $repository->save($aNewUser);

        $user = $repository->findUserById(6);

        $this->assertEquals($user->getId(), $aNewUser->getId());
    }

    /** @test */
    public function five()
    {
        $aNewUser = new User(6);
        $repository = new InMemoryUserRepository();

        $savedUser = $repository->save($aNewUser);
        $repository->remove($savedUser);

        $user = $repository->findUserById(6);

        $this->assertNull($user);
    }




}
