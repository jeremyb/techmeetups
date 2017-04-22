<?php

declare(strict_types=1);

namespace Application\Event;

use Domain\ReadModel\Event;
use Domain\ReadModel\Events;
use Zend\Feed\Writer\Feed;

final class FeedFactory
{
    public static function create(Events $events) : Feed
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

        /** @var Event $event */
        foreach ($events as $event) {
            $entry = $feed->createEntry();
            $entry->setId($event->link);
            $entry->setTitle($event->name);
            $entry->setLink($event->link);
            $entry->setDateCreated($event->createdAt);
            $entry->setDateModified($event->createdAt);
            $entry->setDescription(
                "Meetup organisé par {$event->groupName} le {$event->fullPlannedAt()} à {$event->venueCity}"
            );
            $entry->setContent(self::generateEventContent($event));

            $feed->addEntry($entry);
        }

        return $feed;
    }

    private static function generateEventContent(Event $event) : string
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
