<?php

declare(strict_types=1);

namespace Meetup\DTO;

use DateTime;

final class Group
{
    /** @var string */
    public $id;
    /** @var string */
    public $name;
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
    /** @var DateTime */
    public $created;

    public static function fromData(array $data)
    {
        $self = new self();
        $self->id = $data['id'];
        $self->name = $data['name'];
        $self->joinMode = $data['join_mode']; // => string(4) "open"
        $self->lat = $data['lat'];
        $self->lon = $data['lon'];
        $self->urlname = $data['urlname'];
        $self->who = $data['who'];
        $self->created = (new DateTime())->setTimestamp($data['created']/1000);

        return $self;
    }
}
