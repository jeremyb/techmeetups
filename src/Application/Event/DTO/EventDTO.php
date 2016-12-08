<?php

namespace Application\Event\DTO;

use Doctrine\Common\Inflector\Inflector;

final class EventDTO
{
    /** @var string */
    public $providerId;
    /** @var string */
    public $name;
    /** @var string */
    public $description;
    /** @var string */
    public $link;
    /** @var int (in minutes) */
    public $duration;
    /** @var \DateTimeImmutable */
    public $plannedAt;
    /** @var string */
    public $venueName;
    /** @var string */
    public $venueAddress;
    /** @var string */
    public $venueCity;
    /** @var string */
    public $venueCountry;
    /** @var string */
    public $groupName;

    public static function fromData(array $data)
    {
        $self = new self();
        foreach ($data as $fieldName => $fieldValue) {
            $property = Inflector::camelize($fieldName);
            if (!property_exists($self, $property)) {
                throw new \InvalidArgumentException(sprintf(
                    'Cannot modify the field "%s".',
                    $fieldName
                ));
            }

            $self->$property = $fieldValue;
        }

        return $self;
    }
}
