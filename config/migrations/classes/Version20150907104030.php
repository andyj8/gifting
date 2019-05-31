<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Replace delivered flag column with deliveries table.
 */
class Version20150907104030 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->dropColumn('delivered');

        $deliveries = $schema->createTable('deliveries');
        $deliveries->addColumn('id', 'integer',
            ['unsigned' => true, 'autoincrement'=>true]);
        $deliveries->addColumn('gift_id', 'integer');
        $deliveries->addColumn('transport', 'string');
        $deliveries->addColumn('request', 'text');
        $deliveries->addColumn('response', 'text');
        $deliveries->addColumn('success', 'boolean');

        $deliveries->setPrimaryKey(['id']);
        $deliveries->addIndex(['gift_id', 'success']);
        $deliveries->addForeignKeyConstraint('gifts', ['gift_id'], ['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->addColumn('delivered', 'boolean');

        $schema->dropTable('deliveries');
    }
}
