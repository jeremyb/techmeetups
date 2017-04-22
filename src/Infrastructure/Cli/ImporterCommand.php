<?php

declare(strict_types=1);

namespace Infrastructure\Cli;

use Application\EventImporter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

final class ImporterCommand extends Command
{
    /** @var EventImporter */
    private $eventImporter;

    public function __construct(EventImporter $eventImporter)
    {
        parent::__construct();

        $this->eventImporter = $eventImporter;
    }

    protected function configure() : void
    {
        $this
            ->setName('event:import')
            ->setDescription('Import new or past city events')
            ->addOption('past', null, InputOption::VALUE_NONE, 'Import past events')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output) : void
    {
        if ($input->getOption('past')) {
            $output->writeln('<info>Import past events</info>');
            $count = $this->eventImporter->importPast();
            $output->writeln(sprintf('<info>%s imported events</info>', $count));
        }

        $output->writeln('<info>Import upcoming events</info>');
        $count = $this->eventImporter->importUpcoming();
        $output->writeln(sprintf('<info>%s imported events</info>', $count));
    }
}
