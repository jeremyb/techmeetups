<?php

use Application\City\CityConfigurationRepositoryFactory;
use Application\Event\EventProvider;
use Application\Event\Synchronizer;
use Behat\Behat\Context\Context;
use Domain\Model\City\City;
use Domain\Model\City\CityConfigurationRepository;
use Domain\Model\Event\Event;
use Infrastructure\Persistence\InMemory\InMemoryCityRepository;
use Infrastructure\Persistence\InMemory\InMemoryEventRepository;
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

        $this->citiesConfiguration = CityConfigurationRepositoryFactory::create(
            $this->cities,
            [
                [
                    'name' => 'Montpellier',
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
                        Event::named('First event'),
                        Event::named('Second event'),
                    ];
                }
            },
            $this->events
        );

        $synchronizer->synchronize();
    }

    /**
     * @Then I should have some new events
     */
    public function iShouldHaveSomeNewEvents()
    {
        Assert::count($this->events->findAll(), 2);
    }
}
