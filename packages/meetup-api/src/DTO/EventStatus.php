<?php

declare(strict_types=1);

namespace Meetup\DTO;

final class EventStatus
{
    const CANCELLED = 'cancelled';
    const DRAFT = 'draft';
    const PAST = 'past';
    const PROPOSED = 'proposed';
    const SUGGESTED = 'suggested';
    const UPCOMING = 'upcoming';

    /** @var string */
    public $status;

    public function __construct($status)
    {
        $this->status = $status;
    }
}
