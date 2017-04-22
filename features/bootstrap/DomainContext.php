<?php

declare(strict_types=1);

use Application\DTO\EventDTO;
use Application\DTO\EventDTOCollection;
use Application\EventImporter;
use Application\EventProvider;
use Domain\Model\City\CityConfiguration;
use Behat\Behat\Context\Context;
use Domain\Model\City\City;
use Domain\Model\City\CityConfigurationRepository;
use Infrastructure\Persistence\InMemory\InMemoryCityConfigurationRepositoryFactory;
use Infrastructure\Persistence\InMemory\InMemoryCityRepository;
use Infrastructure\Persistence\InMemory\InMemoryEventRepository;
use Infrastructure\ReadModel\InMemory\InMemoryEventFinder;
use Psr\Log\NullLogger;
use Webmozart\Assert\Assert;

final class DomainContext implements Context
{
    /** @var InMemoryCityRepository */
    private $cities;
    /** @var City */
    private $defaultCity;
    /** @var InMemoryEventRepository */
    private $events;
    /** @var InMemoryEventFinder */
    private $eventFinder;
    /** @var CityConfigurationRepository */
    private $citiesConfiguration;

    public function __construct()
    {
        $this->cities = new InMemoryCityRepository();
        $this->events = new InMemoryEventRepository();
        $this->eventFinder = new InMemoryEventFinder($this->events);
    }

    /**
     * @Given a city is configured with some Meetup groups to fetch
     */
    public function aCityIsConfiguredWithSomeMeetupGroupsToFetch()
    {
        $this->defaultCity = City::named('Montpellier');
        $this->cities->add($this->defaultCity);

        $this->citiesConfiguration = InMemoryCityConfigurationRepositoryFactory::create(
            $this->cities,
            [
                [
                    'city' => 'Montpellier',
                    'providers' => [
                        'meetup.com' => [
                            'Montpellier-PHP-Meetup',
                        ],
                    ],
                ]
            ]
        );
    }

    /**
     * @When the events are synchronized
     */
    public function theEventsAreSynchronized()
    {
        /** @var EventProvider $eventProvider */
        $eventProvider = new class implements EventProvider {
            public function importPastEvents(CityConfiguration $cityConfiguration): EventDTOCollection
            {
                throw new \RuntimeException('Not supported on test');
            }

            public function getUpcomingEvents(CityConfiguration $cityConfiguration) : EventDTOCollection
            {
                return new EventDTOCollection(
                    EventDTO::fromData([
                        'provider_id' => '123',
                        'name' => 'First event',
                        'description' => 'lorem ipsum',
                        'link' => 'https://www.meetup.com/',
                        'duration' => 120,
                        'created_at' => new \DateTimeImmutable(),
                        'planned_at' => new \DateTimeImmutable(),
                        'number_of_members' => 50,
                        'limit_of_members' => 60,
                        'venue_city' => 'Montpellier',
                        'group_name' => 'AFUP Montpellier',
                    ])
                );
            }
        };

        $importer = new EventImporter(
            $this->citiesConfiguration,
            $eventProvider,
            $this->events,
            new NullLogger()
        );
        $importer->importUpcoming();
    }

    /**
     * @Then I should have some new events synchronized
     */
    public function iShouldHaveSomeNewEventsSynchronized()
    {
        Assert::count($this->eventFinder->findNextEvents(), 1);
    }
}
