<?php

declare(strict_types=1);

namespace Domain\Model\Event;

final class Group
{
    /** @var GroupId */
    private $id;
    /** @var string */
    private $name;
    /** @var string */
    public $link;
    /** @var string */
    public $description;
    /** @var string */
    private $photoUrl;
    /** @var \DateTimeImmutable */
    public $createdAt;

    public function __construct(
        GroupId $id,
        string $name,
        string $link,
        ?string $description,
        ?string $photoUrl,
        \DateTimeImmutable $createdAt
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->link = $link;
        $this->description = $description;
        $this->photoUrl = $photoUrl;
        $this->createdAt = $createdAt;
    }

    public function getId() : GroupId
    {
        return $this->id;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getLink() : string
    {
        return $this->link;
    }

    public function getDescription() : ?string
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
