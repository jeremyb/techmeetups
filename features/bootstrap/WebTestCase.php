<?php

declare(strict_types=1);

namespace Behat\Features;

use Doctrine\DBAL\DriverManager;
use Infrastructure\Symfony\AppKernel;
use Meetup\Meetup;
use Prophecy\Prophet;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Component\Console\Output\NullOutput;
use Symfony\Component\DependencyInjection\Container;

final class WebTestCase
{
    /** @var AppKernel */
    private $kernel;
    /** @var Client */
    private $client;
    /** @var Prophet */
    private $prophet;

    private function bootKernel() : void
    {
        if (null !== $this->kernel) {
            return;
        }

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

    public function getContainer() : Container
    {
        $this->bootKernel();

        return $this->kernel->getContainer();
    }

    public function getClient() : Client
    {
        if (null !== $this->client) {
            return $this->client;
        }

        $this->client = $this->getContainer()->get('test.client');
        $this->client->disableReboot();

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
