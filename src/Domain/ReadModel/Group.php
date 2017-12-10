<?php

declare(strict_types=1);

namespace Domain\ReadModel;

use Doctrine\Common\Inflector\Inflector;

final class Group
{
    /** @var string */
    public $groupId;
    /** @var string */
    public $name;
    /** @var string */
    public $description;
    /** @var string */
    public $link;
    /** @var string */
    public $photoUrl;
    /** @var \DateTimeImmutable */
    public $createdAt;
    /** @var int */
    public $numberOfEvents;
    /** @var \DateTimeImmutable */
    public $lastEvent;
    /** @var \DateTimeImmutable */
    public $nextEvent;

    public static function fromData(array $data) : self
    {
        $self = new self();
        foreach ($data as $fieldName => $fieldValue) {
            $property = Inflector::camelize($fieldName);
            if (!property_exists($self, $property)) {
                continue;
            }

            $self->$property = $fieldValue;
        }

        return $self;
    }
}
