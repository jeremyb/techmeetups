<?php

declare(strict_types=1);

namespace Meetup\DTO\Query;

use Meetup\Hydrator\HydratorFactory;

final class FindGroupsQuery
{
    /** @var string */
    public $order;
    /** @var string */
    public $category;
    /** @var string */
    public $country;
    /** @var bool */
    public $fallbackSuggestions;
    /** @var string */
    public $fields;
    /** @var string */
    public $filter;
    /** @var float */
    public $lat;
    /** @var float */
    public $lon;
    /** @var string */
    public $location;
    /** @var int */
    public $radius;
    /** @var string */
    public $selfGroups;
    /** @var string */
    public $text;
    /** @var string */
    public $topicId;
    /** @var bool */
    public $upcomingEvents;
    /** @var string */
    public $zip;

    public static function from(array $data) : self
    {
        return HydratorFactory::create()->hydrate($data, new self());
    }
}
