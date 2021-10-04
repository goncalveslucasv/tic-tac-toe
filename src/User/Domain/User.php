<?php
declare(strict_types=1);

namespace App\User\Domain;

class User
{
    private UserId $id;

    private string $sign;

    public function __construct(UserId $id)
    {
        $this->id = $id;
    }

    public function sign(string $sign): User
    {
        $this->sign = $sign;

        return $this;
    }

    public function getId(): int
    {
        return $this->id->getId();
    }

    public function getSign()
    {
        return $this->sign;
    }
}
