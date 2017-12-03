<?php

declare(strict_types=1);

namespace Behat\Features;

use Doctrine\DBAL\DriverManager;
use Infrastructure\Symfony\AppKernel;
use Meetup\Meetup;
use Prophecy\Prophet;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ResettableContainerInterface;
use Symfony\Component\HttpKernel\Kernel;

final class WebTestCase
{
    /** @var AppKernel */
    private $kernel;
    /** @var Client */
    private $client;
    /** @var Prophet */
    private $prophet;

    public function bootKernel() : void
    {
        $this->ensureKernelShutdown();

        error_reporting(-1);

        $this->prophet = new Prophet();

        $this->kernel = new AppKernel('test', true);
        $this->kernel->boot();

        $this->kernel->getContainer()->set(
            'doctrine.dbal_connection',
            DriverManager::getConnection([
                'driver' => 'pdo_sqlite',
                'path' => sprintf('%s/data.sqlite', __DIR__.'/../../var/cache/test'),
            ])
        );

        $meetupProphesized = $this->prophet->prophesize(Meetup::class);

        $this->kernel->getContainer()->set(
            'app.meetup_client',
            $meetupProphesized->reveal()
        );
        $this->kernel->getContainer()->set(
            'app.meetup_client.prophecy',
            $meetupProphesized
        );
    }

    public function ensureKernelShutdown() : void
    {
        if (null !== $this->kernel) {
            $container = $this->kernel->getContainer();
            $this->kernel->shutdown();
            if ($container instanceof ResettableContainerInterface) {
                $container->reset();
            }

            $this->client = null;
        }
    }

    public function getKernel() : Kernel
    {
        if (null === $this->kernel) {
            throw new \RuntimeException('Kernel must be booted!');
        }

        return $this->kernel;
    }


    public function getContainer() : ContainerInterface
    {
        return $this->getKernel()->getContainer();
    }

    public function getClient() : Client
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $this->client = $this->getContainer()->get('test.client');
        $this->client->setServerParameters([]);

        return $this->client;
    }

    public function getProphet() : Prophet
    {
        return $this->prophet;
    }

    public function initializeDatabase() : void
    {
        /** @var \DbalSchema\DbalSchemaCommand $schema */
        $schema = $this->getContainer()->get('doctrine.dbal_schema.base_command');

        $output = new NullOutput();
        $schema->purge(true, $output);
        $schema->update(true, $output);
    }
}
