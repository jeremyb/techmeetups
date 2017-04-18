<?php

declare(strict_types=1);

namespace Application\Event;

use Domain\ReadModel\Event;
use Domain\ReadModel\EventFinder;
use Zend\Feed\Writer\Feed;

final class FeedFactory
{
    public static function generate(EventFinder $eventFinder) : Feed
    {
        $feed = new Feed();
        $feed->setEncoding('UTF-8');
        $feed->setId('https://techmeetups.fr/montpellier.atom');
        $feed->setLink('https://techmeetups.fr/');
        $feed->setTitle('TechMeetups - L\'agenda des meetups du numérique à Montpellier');
        $feed->setDescription('TechMeetups - L\'agenda des meetups du numérique à Montpellier');
        $feed->setFeedLink('https://techmeetups.fr/montpellier.rss', 'rss');
        $feed->setFeedLink('https://techmeetups.fr/montpellier.atom', 'atom');
        $feed->setLastBuildDate(new \DateTime());
        $feed->setDateModified(new \DateTime());

        foreach ($eventFinder->findNextEvents() as $event) {
            $entry = $feed->createEntry();
            $entry->setTitle($event->name);
            $entry->setLink($event->link);
            $entry->setDateModified(new \DateTime());
            $entry->setDescription(
                "Meetup organisé par {$event->groupName} le {$event->fullPlannedAt()} à {$event->venueCity}"
            );
            $entry->setContent(self::generateEventContent($event));

            $feed->addEntry($entry);
        }

        return $feed;
    }

    private static function generateEventContent(Event $event)
    {
        return <<<HTML
<p>{$event->description}</p>
<ul>
    <li>{$event->groupName}</li>
    <li>{$event->fullPlannedAt()}</li>
    <li>{$event->venueAddress}, {$event->venueCity}</li>
    <li><a href="{$event->link}" target="_blank">{$event->link}</a></li>
</ul>
HTML;
    }

    private function __construct()
    {
    }
}
