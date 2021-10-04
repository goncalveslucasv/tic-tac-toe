<?php
declare(strict_types=1);

namespace App\User\Domain;

class User
{
    private int $id;

    private string $sign;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function create(int $userId): User
    {
        return new self($userId);
    }

    public function sign(string $sign): User
    {
        $this->sign = $sign;

        return $this;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getSign()
    {
        return $this->sign;
    }
}
