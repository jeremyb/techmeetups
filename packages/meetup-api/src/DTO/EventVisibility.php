<?php
declare(strict_types=1);

namespace Meetup\DTO;

final class EventVisibility
{
    const PUBLIC = 'public';
    const PUBLIC_LIMITED = 'public_limited';
    const MEMBERS = 'members';

    /** @var string */
    public $status;

    public function __construct($status)
    {
        $this->status = $status;
    }
}
