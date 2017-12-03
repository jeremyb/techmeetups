<?php

declare(strict_types=1);

namespace Behat\Features;

use Behat\Behat\Context\Context;
use Domain\Model\City\Cities;
use Meetup\DTO\Event;
use Meetup\DTO\Query\FindUpcomingEventsQuery;
use Meetup\Resource\Events;
use Prophecy\Argument;
use Sabre\VObject\Reader;
use Symfony\Component\HttpFoundation\Response;
use Webmozart\Assert\Assert;

final class WebContext implements Context
{
    /** @var WebTestCase */
    private $webTestCase;

    public function __construct()
    {
        $this->webTestCase = new WebTestCase();
    }

    /**
     * @BeforeScenario
     */
    public function beforeScenario()
    {
        $this->webTestCase->bootKernel();
        $this->webTestCase->initializeDatabase();
    }

    /**
     * @AfterScenario
     */
    public function afterScenario()
    {
        $this->webTestCase->ensureKernelShutdown();
    }

    /**
     * @AfterStep
     */
    public function afterStep()
    {
        $this->webTestCase->getProphet()->checkPredictions();
    }

    /**
     * @Given a city is configured with some Meetup groups to fetch
     */
    public function aCityIsConfiguredWithSomeMeetupGroupsToFetch()
    {
        /** @var Cities $cities */
        $cities = $this->webTestCase->getContainer()->get('app.cities');
        Assert::count($cities, 1);
    }

    /**
     * @When the events are synchronized
     */
    public function theEventsAreSynchronized()
    {
        $meetup = $this->webTestCase->getContainer()->get('app.meetup_client.prophecy');
        $events = $this->webTestCase->getProphet()->prophesize(Events::class);
        $meetup->events()->willReturn($events);
        $events
            ->findUpcomingEvents(Argument::type(FindUpcomingEventsQuery::class))
            ->shouldBeCalled()
            ->willReturn([
                Event::fromData([
                    'id' => '235957132',
                    'created' => 1480599839000,
                    'time' => strtotime('+1 week') * 1000,
                    'updated' => 1480601873000,
                    'name' => 'First event',
                    'status' => 'upcoming',
                    'utc_offset' => 3600000,
                    'waitlist_count' => 0,
                    'yes_rsvp_count' => 10,
                    'group' => [
                        'id' => 18724486,
                        'created' => 1436255797000,
                        'name' => 'AFUP Montpellier',
                        'description' => '',
                        'join_mode' => 'open',
                        'lat' => 43.61000061035156,
                        'lon' => 3.869999885559082,
                        'urlname' => 'Montpellier-PHP-Meetup',
                        'who' => 'Membres'
                    ],
                    'link' => 'https://www.meetup.com/Montpellier-PHP-Meetup/events/235957132/',
                    'description' => '<p>Lorem ipsum</p>',
                    'visibility' => 'public',
                ])
            ]);

        /** @var \Application\EventSynchronizer $synchronizer */
        $synchronizer = $this->webTestCase->getContainer()->get('app.event_synchronizer');
        $synchronizer->synchronize();
    }

    /**
     * @Then I should see some events on the homepage
     */
    public function iShouldSeeSomeEventsOnTheHomepage()
    {
        $client = $this->webTestCase->getClient();
        $crawler = $client->request('GET', '/');

        Assert::eq($client->getResponse()->getStatusCode(), Response::HTTP_OK);
        Assert::eq(1, $crawler->filter('#events')->count());
        Assert::contains(
            $crawler->filter('#events li:first-child h3 a')->text(),
            'First event'
        );
    }

    /**
     * @Then a RSS feed is available
     */
    public function aRssFeedIsAvailable()
    {
        $client = $this->webTestCase->getClient();
        $crawler = $client->request('GET', '/montpellier.rss');

        Assert::eq($client->getResponse()->getStatusCode(), Response::HTTP_OK);
        Assert::eq(1, $crawler->filter('rss item')->count());
        Assert::eq('First event', $crawler->filter('rss item title')->text());
    }

    /**
     * @Then an iCal feed is available
     */
    public function anICalFeedIsAvailable()
    {
        $client = $this->webTestCase->getClient();
        $client->request('GET', '/montpellier.ical');
        Assert::eq($client->getResponse()->getStatusCode(), Response::HTTP_OK);

        $calendar = Reader::read($client->getResponse()->getContent());
        Assert::eq(1, count($calendar->VEVENT));
        Assert::eq('First event', (string) $calendar->VEVENT[0]->SUMMARY);
    }
}
