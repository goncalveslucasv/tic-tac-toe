<?php

declare(strict_types=1);

namespace App\Game\Application\Controller\Console;

use App\Game\Application\Command\CreateMovementCommand;
use App\Game\Domain\TicTacToe;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class CreateMovementConsoleCommand extends Command
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
            ->setName('game:movement')
            ->addArgument('userId', InputArgument::REQUIRED, 'User ID')
            ->addArgument('row', InputArgument::REQUIRED, 'Selected row')
            ->addArgument('column', InputArgument::REQUIRED, 'Selected column')
            ->addArgument('gameId', InputArgument::REQUIRED, 'Game ID')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $userId = $input->getArgument('userId');
        $row = $input->getArgument('row');
        $column = $input->getArgument('column');
        $gameId = $input->getArgument('gameId');

        try {
            $command = new CreateMovementCommand((int) $userId, (int) $row, (int) $column, (int) $gameId);
            $response = $this->bus->dispatch($command);

            /** @var TicTacToe $result */
            $result = $response->last(HandledStamp::class)->getResult();

            $output->writeln("Movement done");

            return 1;
        }
        catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return 0;
        }
    }
}