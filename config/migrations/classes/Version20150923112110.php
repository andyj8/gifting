<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add gift created timestamp column.
 */
class Version20150923112110 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->addColumn('created', 'datetime');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->dropColumn('created');
    }
}
