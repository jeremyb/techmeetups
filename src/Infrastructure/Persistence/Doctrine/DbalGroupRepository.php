<?php

declare(strict_types=1);

namespace Infrastructure\Persistence\Doctrine;

use Doctrine\DBAL\Connection;
use Domain\Model\Event\Group;
use Domain\Model\Event\GroupId;
use Domain\Model\Event\GroupRepository;

final class DbalGroupRepository implements GroupRepository
{
    /** @var Connection */
    private $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function addOrUpdate(Group $group): void
    {
        $data = $this->convertGroupToArray($group);

        if (!$this->contains($group->getId())) {
            $this->connection->insert('groups', $data);
        } else {
            $this->connection->update(
                'groups',
                $data,
                ['group_id' => (string) $group->getId()]
            );
        }
    }

    public function contains(GroupId $groupId) : bool
    {
        return (bool) $this->connection
            ->fetchColumn(
                'SELECT EXISTS(SELECT 1 FROM groups WHERE group_id = ?)',
                [(string) $groupId]
            );
    }

    private function convertGroupToArray(Group $group) : array
    {
        $convertDateTimeToUTC = function (\DateTimeImmutable $date) {
            return $date
                ->setTimezone(new \DateTimeZone('UTC'))
                ->format('Y-m-d H:i:s.uP');
        };

        return [
            'group_id' => (string) $group->getId(),
            'name' => $group->getName(),
            'link' => $group->getLink(),
            'description' => $group->getDescription(),
            'photo_url' => $group->getDescription(),
            'created_at' => $convertDateTimeToUTC($group->getCreatedAt()),
        ];
    }
}
