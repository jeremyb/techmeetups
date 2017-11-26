<?php

declare(strict_types=1);

namespace Meetup\DTO\Query;

use Meetup\Hydrator\HydratorFactory;

final class GroupEventsQuery
{
    public const RECENT_PAST = 'recent_past';
    public const NEXT_UPCOMING = 'next_upcoming';
    public const FUTURE_OR_PAST = 'future_or_past';

    public const CANCELLED = 'cancelled';
    public const DRAFT = 'draft';
    public const PAST = 'past';
    public const PROPOSED = 'proposed';
    public const SUGGESTED = 'suggested';
    public const UPCOMING = 'upcoming';

    /** @var boolean */
    public $desc;
    /** @var string */
    public $fields;
    /** @var int */
    public $page;
    /** @var string */
    public $scroll;
    /** @var string */
    public $status;

    public static function from(array $data) : self
    {
        return HydratorFactory::create()->hydrate($data, new self());
    }
}
