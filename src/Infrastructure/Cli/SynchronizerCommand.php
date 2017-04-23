<?php

declare(strict_types=1);

namespace Infrastructure\Cli;

use Application\EventSynchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizerCommand extends Command
{
    /** @var EventSynchronizer */
    private $eventSynchronizer;

    public function __construct(EventSynchronizer $eventSynchronizer)
    {
        parent::__construct();

        $this->eventSynchronizer = $eventSynchronizer;
    }

    protected function configure() : void
    {
        $this
            ->setName('event:sync')
            ->setDescription('Synchronize upcoming city events')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        $output->writeln('<info>Synchronize upcoming events</info>');
        $count = $this->eventSynchronizer->synchronize();
        $output->writeln(sprintf('<info>%s imported events</info>', $count));
    }
}
