<?php

declare(strict_types=1);

namespace Meetup\DTO;

use Meetup\Hydrator\HydratorFactory;

final class Photo
{
    /** @var string */
    public $highresLink;
    /** @var string */
    public $photoLink;
    /** @var string */
    public $thumbLink;

    public static function fromData(array $data) : self
    {
        return HydratorFactory::create()->hydrate($data, new self());
    }
}
