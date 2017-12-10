<?php

declare(strict_types=1);

namespace Domain\ReadModel;

use Doctrine\Common\Inflector\Inflector;

final class Event
{
    /** @var string */
    public $eventId;
    /** @var string */
    public $name;
    /** @var string */
    public $description;
    /** @var string */
    public $link;
    /** @var int (in minutes) */
    public $duration;
    /** @var \DateTime */
    public $createdAt;
    /** @var \DateTime */
    public $plannedAt;
    /** @var int */
    public $numberOfMembers;
    /** @var int */
    public $limitOfMembers;
    /** @var string */
    public $venueName;
    /** @var float */
    public $venueLat;
    /** @var float */
    public $venueLon;
    /** @var string */
    public $venueAddress;
    /** @var string */
    public $venueCity;
    /** @var string */
    public $venueCountry;
    /** @var string */
    public $groupName;

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

    public function extractDescription() : string
    {
        if (empty($this->description)) {
            return '';
        }

        return substr(strip_tags($this->description), 0, 250).'...';
    }

    public function getWeekDay() : string
    {
        return $this->plannedAtFormatter('EEEE')->format($this->plannedAt);
    }

    public function getDay()
    {
        return $this->plannedAt->format('j');
    }

    public function getMonth() : string
    {
        return $this->plannedAtFormatter('MMMM')->format($this->plannedAt);
    }

    public function getYear() : string
    {
        return $this->plannedAtFormatter('YYYY')->format($this->plannedAt);
    }

    public function getHour() : string
    {
        $date = $this->plannedAt;

        return sprintf('%sh%s', $date->format('G'), $date->format('i'));
    }

    public function fullPlannedAt() : string
    {
        $formatter = \IntlDateFormatter::create(
            'fr',
            \IntlDateFormatter::FULL,
            \IntlDateFormatter::SHORT,
            $this->plannedAt->getTimezone()
        );

        return $formatter->format($this->plannedAt->getTimestamp());
    }

    private function plannedAtFormatter(string $pattern) : \IntlDateFormatter
    {
        return \IntlDateFormatter::create(
            'fr',
            \IntlDateFormatter::NONE,
            \IntlDateFormatter::NONE,
            $this->plannedAt->getTimezone(),
            null,
            $pattern
        );
    }

    public function getEndedAt() : \DateTime
    {
        $ended = clone $this->plannedAt;
        $ended->modify(sprintf('+%d minutes', $this->duration ?: 180));

        return $ended;
    }

    public function fullVenueAddress() : string
    {
        return sprintf(
            '%s (%s)',
            $this->venueName,
            implode(', ', array_filter([
                $this->venueAddress,
                $this->venueCity,
                $this->venueCountry,
            ]))
        );
    }
}
