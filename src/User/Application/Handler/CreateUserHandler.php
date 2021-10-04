<?php

namespace App\User\Application\Handler;

use App\User\Application\Command\CreateUserCommand;
use App\User\Domain\User;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CreateUserHandler implements MessageHandlerInterface
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(CreateUserCommand $message): User
    {
        $user = new User($message->getId());

        return $this->userRepository->save($user);
    }
}