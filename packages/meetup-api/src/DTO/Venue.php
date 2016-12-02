<?php
declare(strict_types=1);

namespace Meetup\DTO;

final class Venue
{
    /** @var string */
    public $id;
    /** @var string */
    public $name;
    /** @var float */
    public $lat;
    /** @var float */
    public $lon;
    /** @var string */
    public $address1;
    /** @var string */
    public $city;
    /** @var string */
    public $country;
    /** @var string */
    public $localizedCountryName;

    public static function fromData(array $data)
    {
        $self = new self();
        $self->id = $data['id'];
        $self->name = $data['name'];
        $self->lat = $data['lat'];
        $self->lon = $data['lon'];
        $self->address1 = $data['address_1'];
        $self->city = $data['city'];
        $self->country = $data['country'];
        $self->localizedCountryName = $data['localized_country_name'];

        return $self;
    }
}
