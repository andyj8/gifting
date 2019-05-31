<?php

require_once __DIR__ . '/../../vendor/autoload.php';

exec('cd config/migrations; ../../vendor/bin/doctrine-migrations --db-configuration=migrations-db-test.php --no-interaction migrations:migrate');
