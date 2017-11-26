<?php

declare(strict_types=1);

namespace Meetup\DTO;

final class EventStatus
{
    public const CANCELLED = 'cancelled';
    public const DRAFT = 'draft';
    public const PAST = 'past';
    public const PROPOSED = 'proposed';
    public const SUGGESTED = 'suggested';
    public const UPCOMING = 'upcoming';

    /** @var string */
    public $status;

    public function __construct(string $status)
    {
        $this->status = $status;
    }
}
