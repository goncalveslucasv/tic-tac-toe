<?php

namespace App\User\Application\Handler;

use App\User\Application\Command\DeleteUserCommand;
use App\User\Domain\UserNotFoundException;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class DeleteUserHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(DeleteUserCommand $message)
    {
        $user = $this->userRepository->findUserById($message->getId());

        if(empty($user)){
            throw new UserNotFoundException('user not found');
        }

        $this->userRepository->remove($user);
    }
}