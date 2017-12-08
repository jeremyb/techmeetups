<?php

declare(strict_types=1);

namespace Behat\Features;

use DbalSchema\DbalSchemaCommand;
use Infrastructure\Symfony\Kernel;
use Prophecy\Prophet;
use Psr\Container\ContainerInterface;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\ResettableContainerInterface;

final class WebTestCase
{
    /** @var Kernel */
    private $kernel;
    /** @var Client */
    private $client;
    /** @var Prophet */
    private $prophet;

    public function bootKernel() : void
    {
        $this->ensureKernelShutdown();

        error_reporting(-1);

        $this->kernel = new FunctionalTestKernel();
        $this->kernel->boot();
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
        $schema = $this->getContainer()->get(DbalSchemaCommand::class);

        $output = new NullOutput();
        $schema->purge(true, $output);
        $schema->update(true, $output);
    }
}
