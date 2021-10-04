<?php

declare(strict_types=1);

namespace App\User\Application\Controller\Console;
use App\User\Application\Command\DeleteUserCommand;
use App\User\Application\Query\FindUserByIdQuery;
use App\User\Domain\Error\UserNotFoundException;
use App\User\Domain\User;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class DeleteUserConsoleCommand extends Command
{
    /**
     * @var MessageBusInterface
     */
    private $bus;

    public function __construct(MessageBusInterface $bus)
    {
        parent::__construct();
        $this->bus = $bus;
    }

    protected function configure(): void
    {
        $this
            ->setName('user:delete')
            ->addArgument('userId', InputArgument::REQUIRED, 'User ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $userId = $input->getArgument('userId');

        $response = $this->bus->dispatch(new FindUserByIdQuery((int) $userId));
        $user = $response->last(HandledStamp::class)->getResult();


        if (!isset($user)) {
            throw new \Exception("User doesn't exists");
        }

        try {
            $command = new DeleteUserCommand((int) $userId);
            $this->bus->dispatch($command);

            $output->writeln("User deleted ID:". $userId);

            return 1;
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return 0;
        }

    }
}