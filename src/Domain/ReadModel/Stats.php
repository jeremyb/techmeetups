<?php

declare(strict_types=1);

namespace Domain\ReadModel;

final class Stats
{
    /** @var int */
    public $numberOfEvents;
    /** @var int */
    public $averageRegistrations;
    /** @var \DateTimeImmutable */
    public $firstEventAt;
    /** @var \DateTimeImmutable */
    public $lastEventAt;
    /** @var array */
    public $numberOfEventsPerYear;
    /** @var array */
    public $numberOfEventsPerMonth;
    /** @var array */
    public $popularEvents;
    /** @var array */
    public $popularGroups;

    public static function create(array $data) : self
    {
        $self = new self();
        $self->numberOfEvents = $data['number_of_events'] ?? 0;
        $self->averageRegistrations = $data['average_registrations'] ?? 0;
        $self->firstEventAt = isset($data['first_event_at']) ? new \DateTimeImmutable($data['first_event_at']) : null;
        $self->lastEventAt = isset($data['last_event_at']) ? new \DateTimeImmutable($data['last_event_at']) : null;
        $self->numberOfEventsPerYear = $data['number_of_events_per_year'] ?? [];
        $self->numberOfEventsPerMonth = $data['number_of_events_per_month'] ?? [];
        $self->popularEvents = $data['popular_events'] ?? [];
        $self->popularGroups = $data['popular_groups'] ?? [];

        return $self;
    }

    public function toArray() : array
    {
        return [
            'number_of_events' => $this->numberOfEvents,
            'average_registrations' => $this->averageRegistrations,
            'first_event_at' => $this->firstEventAt,
            'last_event_at' => $this->lastEventAt,
            'number_of_events_per_year' => $this->numberOfEventsPerYear,
            'number_of_events_per_month' => $this->numberOfEventsPerMonth,
            'popular_events' => $this->popularEvents,
            'popular_groups' => $this->popularGroups,
        ];
    }
}
