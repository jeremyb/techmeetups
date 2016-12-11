<?php

use Application\Event\DTO\EventDTO;
use Application\Event\EventProvider;
use Application\Event\Synchronizer;
use Behat\Behat\Context\Context;
use Domain\Model\City\City;
use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Infrastructure\Persistence\InMemory\InMemoryCityConfigurationRepositoryFactory;
use Infrastructure\Persistence\InMemory\InMemoryCityRepository;
use Infrastructure\Persistence\InMemory\InMemoryEventRepository;
use Psr\Log\NullLogger;
use Webmozart\Assert\Assert;

class DomainContext implements Context
{
    /** @var InMemoryCityRepository */
    private $cities;
    /** @var City */
    private $defaultCity;
    /** @var InMemoryEventRepository */
    private $events;
    /** @var CityConfigurationRepository */
    private $citiesConfiguration;

    public function __construct()
    {
        $this->cities = new InMemoryCityRepository();
        $this->events = new InMemoryEventRepository();
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
        $synchronizer = new Synchronizer(
            $this->citiesConfiguration,
            new class implements EventProvider {
                public function getEvents(array $sources): array
                {
                    return [
                        EventDTO::fromData([
                            'provider_id' => '123',
                            'name' => 'First event',
                            'description' => 'lorem ipsum',
                            'link' => 'https://www.meetup.com/',
                            'duration' => 120,
                            'planned_at' => new \DateTimeImmutable(),
                            'venue_city' => 'Montpellier',
                            'group_name' => 'AFUP Montpellier',
                        ]),
                    ];
                }
            },
            $this->events,
            new NullLogger()
        );

        $synchronizer->synchronize();
    }

    /**
     * @Then I should have some new events
     */
    public function iShouldHaveSomeNewEvents()
    {
        Assert::count($this->events->findAll(), 1);
    }
}
