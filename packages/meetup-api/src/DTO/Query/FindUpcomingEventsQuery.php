<?php

declare(strict_types=1);

namespace Meetup\DTO\Query;

use Meetup\Hydrator\HydratorFactory;

final class FindUpcomingEventsQuery
{
    /** @var string */
    public $startDateRange;
    /** @var string */
    public $startTimeRange;
    /** @var string */
    public $endDateRange;
    /** @var string */
    public $endTimeRange;
    /** @var string */
    public $excludedGroups;
    /** @var string */
    public $fields;
    /** @var float */
    public $lat;
    /** @var float */
    public $lon;
    /** @var string */
    public $order;
    /** @var string */
    public $page;
    /** @var string */
    public $radius;
    /** @var string */
    public $selfGroups;
    /** @var string */
    public $text;
    /** @var string */
    public $topicCategory;

    public static function from(array $data) : self
    {
        return HydratorFactory::create()->hydrate($data, new self());
    }
}
