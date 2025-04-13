<?php

namespace App\Shared\Infrastructure\Command;

use App\Shared\Infrastructure\Event\DoctrineEventStore;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

#[AsCommand(name: 'app:replay-events')]
class ReplayEventsCommand extends Command
{
    public function __construct(private DoctrineEventStore $store, private EventDispatcherInterface $dispatcher) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        foreach ($this->store->all() as $event) {
            $this->dispatcher->dispatch($event);
        }

        $output->writeln('All events replayed.');
        return Command::SUCCESS;
    }
}
