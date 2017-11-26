<?php

declare(strict_types=1);

namespace Domain\Model\Event;

final class Group
{
    /** @var string */
    private $name;
    /** @var string */
    public $slug;
    /** @var string */
    public $description;
    /** @var string */
    private $photoUrl;
    /** @var \DateTimeImmutable */
    public $createdAt;

    public function __construct(
        string $name,
        string $slug,
        ?string $description,
        ?string $photoUrl,
        \DateTimeImmutable $createdAt
    ) {
        $this->name = $name;
        $this->slug = $slug;
        $this->description = $description;
        $this->photoUrl = $photoUrl;
        $this->createdAt = $createdAt;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getSlug() : string
    {
        return $this->slug;
    }

    public function getDescription() : string
    {
        return $this->description;
    }

    public function getPhotoUrl() : ?string
    {
        return $this->photoUrl;
    }

    public function getCreatedAt() : \DateTimeImmutable
    {
        return $this->createdAt;
    }
}
