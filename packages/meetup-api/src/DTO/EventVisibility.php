<?php

declare(strict_types=1);

namespace Meetup\DTO;

final class EventVisibility
{
    public const PUBLIC = 'public';
    public const PUBLIC_LIMITED = 'public_limited';
    public const MEMBERS = 'members';

    /** @var string */
    public $value;

    public function __construct(string $value)
    {
        $this->value = $value;
    }
}
