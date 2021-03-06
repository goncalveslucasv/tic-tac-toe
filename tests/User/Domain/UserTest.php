<?php
declare(strict_types=1);

namespace App\Tests\User\Domain;

use App\User\Domain\User;
use App\User\Domain\UserId;
use PHPUnit\Framework\TestCase as PHPUnit_TestCase;

class UserTest extends PHPUnit_TestCase
{
    const AN_USER_ID = 2;

    /** @test */
    public function it_should_create_a_user_with_a_valid_id()
    {
        $user = new User(new UserId(self::AN_USER_ID));

        $this->assertEquals(self::AN_USER_ID, $user->getId());
    }
}
