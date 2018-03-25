<?php

declare(strict_types=1);

namespace Behat\Features;

use Application\EventSynchronizer;
use Behat\Behat\Context\Context;
use Domain\Model\City\Cities;
use Domain\Model\City\City;
use Domain\Model\Event\Events;
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
    public function beforeScenario() : void
    {
        $this->webTestCase->bootKernel();
        $this->webTestCase->initializeDatabase();
    }

    /**
     * @AfterScenario
     */
    public function afterScenario() : void
    {
        $this->webTestCase->ensureKernelShutdown();
    }

    /**
     * @Given a city is configured with some Meetup groups to fetch
     */
    public function aCityIsConfiguredWithSomeMeetupGroupsToFetch() : void
    {
        /** @var Cities $cities */
        $cities = $this->webTestCase->getContainer()->get(Cities::class);
        Assert::count($cities, 1);
    }

    /**
     * @When the events are synchronized
     */
    public function theEventsAreSynchronized() : void
    {
        /** @var \Application\EventSynchronizer $synchronizer */
        $synchronizer = $this->webTestCase->getContainer()->get(EventSynchronizer::class);
        $synchronizer->synchronize();
    }

    /**
     * @Then I should see some events on the homepage
     */
    public function iShouldSeeSomeEventsOnTheHomepage() : void
    {
        $client = $this->webTestCase->getClient();
        $crawler = $client->request('GET', '/');

        Assert::eq($client->getResponse()->getStatusCode(), Response::HTTP_OK);
//        echo $client->getResponse()->getContent(); exit;
        Assert::eq(1, $crawler->filter('#calendar .event')->count());
        Assert::contains(
            $crawler->filter('#calendar .event:first-child .title a')->eq(1)->text(),
            'First event'
        );
    }

    /**
     * @Then a RSS feed is available
     */
    public function aRssFeedIsAvailable() : void
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
    public function anICalFeedIsAvailable() : void
    {
        $client = $this->webTestCase->getClient();
        $client->request('GET', '/montpellier.ical');
        Assert::eq($client->getResponse()->getStatusCode(), Response::HTTP_OK);

        $calendar = Reader::read($client->getResponse()->getContent());
        Assert::eq(1, count($calendar->VEVENT));
        Assert::eq('First event', (string) $calendar->VEVENT[0]->SUMMARY);
    }
}
