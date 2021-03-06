<?php

declare(strict_types=1);

namespace App\Game\Application\Controller\Console;

use App\Game\Application\Command\StartGameCommand;
use App\Game\Domain\TicTacToe;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class StartGameConsoleCommand extends Command
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
            ->setName('game:start')
            ->addArgument('userOneId', InputArgument::REQUIRED, 'User ID one')
            ->addArgument('userTwoId', InputArgument::REQUIRED, 'User ID two')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userOneId = $input->getArgument('userOneId');
        $userTwoId = $input->getArgument('userTwoId');


        try {
            $command = new StartGameCommand((int) $userOneId, (int) $userTwoId, 1);

            $this->bus->dispatch($command);

            $output->writeln("Game was created");

            return 1;
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return 0;
        }

    }
}