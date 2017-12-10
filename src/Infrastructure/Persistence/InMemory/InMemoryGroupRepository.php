<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\InMemory;

use Domain\Model\Event\Group;
use Domain\Model\Event\GroupId;
use Domain\Model\Event\GroupRepository;

final class InMemoryGroupRepository implements GroupRepository
{
    /** @var Group[] */
    private $groups;

    public function addOrUpdate(Group $group): void
    {
        $this->groups[(string) $group->getId()] = $group;
    }

    public function contains(GroupId $groupId): bool
    {
        return isset($this->groups[(string) $groupId]);
    }
}
