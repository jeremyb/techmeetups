<?php

declare(strict_types=1);

namespace Meetup\DTO;

use DateTimeImmutable;
use Meetup\Hydrator\HydratorFactory;

final class Group
{
    public const APPROVAL = 'approval';
    public const CLOSED = 'closed';
    public const OPEN = 'open';

    /** @var string */
    public $id;
    /** @var string */
    public $name;
    /** @var string */
    public $description;
    /** @var string */
    public $joinMode;
    /** @var float */
    public $lat;
    /** @var float */
    public $lon;
    /** @var string */
    public $urlname;
    /** @var string */
    public $who;
    /** @var DateTimeImmutable */
    public $created;
    /** @var null|Photo */
    public $keyPhoto;

    public static function fromData(array $data) : self
    {
        /** @var self $group */
        $group = HydratorFactory::create()->hydrate($data, new self());
        $group->created = (new DateTimeImmutable())->setTimestamp($data['created']/1000);
        $group->keyPhoto = isset($data['key_photo']) ? Photo::fromData($data['key_photo']) : null;

        return $group;
    }
}
