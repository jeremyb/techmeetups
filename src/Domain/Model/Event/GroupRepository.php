<?php

declare(strict_types=1);

namespace Domain\Model\Event;

interface GroupRepository
{
    public function addOrUpdate(Group $group) : void;

    public function contains(GroupId $groupId) : bool;
}
