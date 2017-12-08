<?php

declare(strict_types=1);

namespace Behat\Features;

use Application\EventProvider;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\DriverManager;
use Infrastructure\Symfony\Kernel;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class FunctionalTestKernel extends Kernel
{
    use MicroKernelTrait {
        MicroKernelTrait::configureContainer as protected configureContainer;
    }

    public function __construct()
    {
        parent::__construct('test', true);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader)
    {
        parent::configureContainer($container, $loader);

        $container
            ->register(Connection::class)
            ->setFactory([DriverManager::class, 'getConnection'])
            ->setArgument(0, [
                'driver' => 'pdo_sqlite',
                'path' => sprintf('%s/data.sqlite', __DIR__.'/../../var/cache/test'),
            ]);

        $container->register(EventProvider::class, InMemoryEventProvider::class);
    }
}
