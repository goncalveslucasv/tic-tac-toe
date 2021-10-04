<?php

declare(strict_types=1);

namespace App\User\Application\Controller\Console;

use App\User\Application\Command\CreateUserCommand;
use App\User\Application\Query\FindUserByIdQuery;
use App\User\Domain\User;
use App\User\Domain\Error\UserAlreadyExistsException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class CreateUserConsoleCommand extends Command
{
    private MessageBusInterface $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this
            ->setName('user:create')
            ->addArgument('userId', InputArgument::REQUIRED, 'User ')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('userId');

        $response = $this->bus->dispatch(new FindUserByIdQuery((int) $userId));
        $user = $response->last(HandledStamp::class)->getResult();


        if (isset($user)) {
            throw new UserAlreadyExistsException('User already exists');
        }

        try {
            $command = new CreateUserCommand((int) $userId);
            $response = $this->bus->dispatch($command);

            /** @var User $result */
            $result = $response->last(HandledStamp::class)->getResult();

            $output->writeln("User created ID:". $result->getId());

            return 1;
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return 0;
        }

    }
}