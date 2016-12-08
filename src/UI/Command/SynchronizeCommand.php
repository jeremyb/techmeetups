<?php
declare(strict_types=1);

namespace UI\Command;

use Application\Event\Synchronizer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

final class SynchronizeCommand extends Command
{
    /** @var Synchronizer */
    private $synchronizer;

    public function __construct(Synchronizer $synchronizer)
    {
        parent::__construct();

        $this->synchronizer = $synchronizer;
    }

    protected function configure()
    {
        $this
            ->setName('meetup:synchronize')
            ->setDescription('')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->synchronizer->synchronize();
    }
}
