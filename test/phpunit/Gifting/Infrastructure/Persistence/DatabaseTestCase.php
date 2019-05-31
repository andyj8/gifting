<?php

namespace Gifting\Test\Infrastructure\Persistence;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\DriverManager;
use PDO;
use PHPUnit_Extensions_Database_DataSet_YamlDataSet;
use PHPUnit_Extensions_Database_Operation_Factory;
use PHPUnit_Extensions_Database_TestCase;

abstract class DatabaseTestCase extends PHPUnit_Extensions_Database_TestCase
{
    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected $dbh;

    protected function setUp()
    {
        parent::setUp();

        $connectionParams = require __DIR__ . '/../../../../../config/migrations/migrations-db-test.php';
        $config = new Configuration();
        $this->dbh = DriverManager::getConnection($connectionParams, $config);
    }

    protected function getSetUpOperation()
    {
        return PHPUnit_Extensions_Database_Operation_Factory::CLEAN_INSERT(true);
    }

    protected function getConnection()
    {
        $connectionParams = require __DIR__ . '/../../../../../config/migrations/migrations-db-test.php';
        unset($connectionParams['driver']);
        $dsn = str_replace('&', ';', http_build_query($connectionParams));
        $connection = new PDO('pgsql:' . $dsn);
        return $this->createDefaultDBConnection($connection);
    }

    protected function getDataSet()
    {
        return new PHPUnit_Extensions_Database_DataSet_YamlDataSet(
            dirname(__FILE__) . "/fixtures/gifts.yml"
        );
    }
}
