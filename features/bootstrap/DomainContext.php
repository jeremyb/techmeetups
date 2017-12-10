<?php

declare(strict_types=1);

namespace Behat\Features;

use Application\EventProvider;
use Application\EventSynchronizer;
use Behat\Behat\Context\Context;
use Domain\Model\City\Cities;
use Domain\Model\City\City;
use Domain\Model\Event\Event;
use Domain\Model\Event\EventId;
use Domain\Model\Event\Events;
use Domain\Model\Event\Group;
use Domain\Model\Event\GroupId;
use Domain\Model\Event\Venue;
use Infrastructure\Persistence\InMemory\InMemoryEventRepository;
use Infrastructure\Persistence\InMemory\InMemoryGroupRepository;
use Infrastructure\ReadModel\InMemory\InMemoryEventFinder;
use Psr\Log\NullLogger;
use Webmozart\Assert\Assert;

final class DomainContext implements Context
{
    /** @var Cities */
    private $cities;
    /** @var City */
    private $defaultCity;
    /** @var InMemoryGroupRepository */
    private $groups;
    /** @var InMemoryEventRepository */
    private $events;
    /** @var InMemoryEventFinder */
    private $eventFinder;

    public function __construct()
    {
        $this->groups = new InMemoryGroupRepository();
        $this->events = new InMemoryEventRepository();
        $this->eventFinder = new InMemoryEventFinder($this->events);
    }

    /**
     * @Given a city is configured with some Meetup groups to fetch
     */
    public function aCityIsConfiguredWithSomeMeetupGroupsToFetch() : void
    {
        $this->defaultCity = City::named('Montpellier', 43.6, 3.8833);
        $this->cities = new Cities($this->defaultCity);
    }

    /**
     * @When the events are synchronized
     */
    public function theEventsAreSynchronized() : void
    {
        /** @var EventProvider $eventProvider */
        $eventProvider = new class() implements EventProvider {
            public function importPastEvents(City $city) : Events
            {
                throw new \RuntimeException('Not supported on test');
            }

            public function getUpcomingEvents(City $city) : Events
            {
                return new Events(
                    Event::create(
                        EventId::fromString('123'),
                        $city,
                        'First event',
                        'lorem ipsum',
                        'https://www.meetup.com/',
                        120,
                        new \DateTimeImmutable(),
                        new \DateTimeImmutable(),
                        50,
                        60,
                        (function () {
                            return new Venue('Somewhere', null, null, null, 'Montpellier', null);
                        })(),
                        (function () {
                            return new Group(
                                GroupId::fromString('321'),
                                'Group',
                                'group',
                                '',
                                '',
                                new \DateTimeImmutable('-2 years')
                            );
                        })()
                    )
                );
            }
        };

        $synchronizer = new EventSynchronizer(
            $this->cities,
            $eventProvider,
            $this->groups,
            $this->events,
            new NullLogger()
        );
        $synchronizer->synchronize();
    }

    /**
     * @Then I should have some new events synchronized
     */
    public function iShouldHaveSomeNewEventsSynchronized() : void
    {
        Assert::count($this->eventFinder->findNextEvents(), 1);
    }
}
