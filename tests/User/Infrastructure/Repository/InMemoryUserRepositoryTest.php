<?php
declare(strict_types=1);

namespace App\Tests\User\Infrastructure\Repository;

use App\User\Domain\User;
use App\User\Domain\UserId;
use App\User\Infrastructure\Repository\InMemoryUserRepository;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class InMemoryUserRepositoryTest extends PHPUnit_TestCase
{
    /** @test */
    public function it_should_return_a_stored_users()
    {
        $repository = new InMemoryUserRepository();

        $users = $repository->findAll();

        $this->assertEquals([
            new User(new UserId(0)),
            new User(new UserId(1)),
            new User(new UserId(2)),
            new User(new UserId(3)),
            new User(new UserId(4)),
            new User(new UserId(5))
        ], $users);
    }

    /** @test */
    public function it_should_return_a_specific_user_when_is_queried()
    {
        $repository = new InMemoryUserRepository();

        $result = $repository->findUserById(2);

        $this->assertEquals(new User(new UserId(2)), $result);
    }

    /** @test */
    public function it_should_not_return_a_specific_user_when_the_query_is_wrong()
    {
        $repository = new InMemoryUserRepository();

        $result = $repository->findUserById(7);

        $this->assertNull($result);
    }

    /** @test */
    public function it_should_save_a_new_user()
    {
        $aNewUser = new User(new UserId(6));
        $repository = new InMemoryUserRepository();

        $repository->save($aNewUser);

        $user = $repository->findUserById(6);

        $this->assertEquals($user->getId(), $aNewUser->getId());
    }

    /** @test */
    public function it_should_remove_an_existent_user()
    {
        $aNewUser = new User(new UserId(6));
        $repository = new InMemoryUserRepository();

        $savedUser = $repository->save($aNewUser);
        $repository->remove($savedUser);

        $user = $repository->findUserById(6);

        $this->assertNull($user);
    }




}
