#!/bin/bash
php bin/console --env=prod dbal:schema:update --force
php bin/console --env=prod event:import -vvv
php bin/console --env=prod event:sync -vvv
