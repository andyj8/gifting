<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Type;

/**
 * Create redemptions table.
 */
class Version20150911165335 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        Type::addType('inet', 'Gifting\Infrastructure\Persistence\Type\InetType');
        $this->connection->getDatabasePlatform()->registerDoctrineTypeMapping('InetType', 'inet');

        $redemptions = $schema->createTable('redemptions');
        $redemptions->addColumn('id', 'integer');
        $redemptions->addColumn('redeemed_at', 'datetime');
        $redemptions->addColumn('gift_id', 'integer');
        $redemptions->addColumn('client_ip', 'inet');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('redemptions');
    }
}
