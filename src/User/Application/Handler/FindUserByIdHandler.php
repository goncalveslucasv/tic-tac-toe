<?php

namespace App\User\Application\Handler;

use App\User\Application\Query\FindUserByIdQuery;
use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindUserByIdHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(FindUserByIdQuery $message): ?User
    {
        return $this->userRepository->findUserById($message->getId());
    }
}