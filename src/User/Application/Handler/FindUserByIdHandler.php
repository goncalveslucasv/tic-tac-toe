<?php

namespace App\User\Application\Handler;

use App\User\Application\Query\FindUserByIdQuery;
use App\User\Domain\UserRepository;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class FindUserByIdHandler implements MessageHandlerInterface
{
    /**
     * @var UserRepository
     */
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function __invoke(FindUserByIdQuery $message)
    {
        return $this->userRepository->findUserById($message->getId());
    }
}