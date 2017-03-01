<?php

declare(strict_types = 1);

namespace Infrastructure\Persistence\Doctrine;

use DbalSchema\SchemaDefinition as SchemaDefinitionInterface;
use Doctrine\DBAL\Schema\Schema;

final class SchemaDefinition implements SchemaDefinitionInterface
{
    public function define(Schema $schema)
    {
        $events = $schema->createTable('events');

        $events->addColumn('event_id', 'string');
        $events->addColumn('name', 'string');
        $events->addColumn('description', 'text');
        $events->addColumn('link', 'string');
        $events->addColumn('duration', 'smallint', ['notnull' => false]);
        $events->addColumn('planned_at', 'datetime');
        $events->addColumn('group_name', 'string', ['notnull' => false]);

        $events->addColumn('venue_name', 'string', ['notnull' => false]);
        $events->addColumn('venue_address', 'string', ['notnull' => false]);
        $events->addColumn('venue_city', 'string', ['notnull' => false]);
        $events->addColumn('venue_country', 'string', ['notnull' => false]);
        $events->addColumn('venue_lat', 'string', ['notnull' => false]);
        $events->addColumn('venue_lon', 'string', ['notnull' => false]);

        $events->setPrimaryKey(['event_id']);
        $events->addIndex(['group_name']);
    }
}
