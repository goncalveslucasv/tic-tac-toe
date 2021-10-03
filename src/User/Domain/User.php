<?php
declare(strict_types=1);

namespace App\User\Domain;

class User
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $sign;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public static function create(int $userId){
        return new self($userId);
    }

    public function sign(string $sign){
        $this->sign = $sign;

        return $this;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    public function getSign()
    {
        return $this->sign;
    }
}
