<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Set redemption primary key.
 */
class Version20150916132242 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $redemptions = $schema->getTable('redemptions');
        $redemptions->dropColumn('id');
        $redemptions->setPrimaryKey(['gift_id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $redemptions = $schema->getTable('redemptions');
        $redemptions->addColumn('id', 'integer');
        $redemptions->dropPrimaryKey();
    }
}
