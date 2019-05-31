<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Drop delivery transport column.
 * Add delivery datetime column.
 */
class Version20150911113215 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $deliveries = $schema->getTable('deliveries');
        $deliveries->dropColumn('transport');
        $deliveries->addColumn('attempted', 'datetime');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $deliveries = $schema->getTable('deliveries');
        $deliveries->addColumn('transport', 'string');
        $deliveries->dropColumn('attempted');
    }
}
