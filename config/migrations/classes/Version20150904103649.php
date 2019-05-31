<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Create styles table
 */
class Version20150904103649 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $styles = $schema->createTable('styles');
        $styles->addColumn('type', 'string');
        $styles->addColumn('ref', 'string');
        $styles->addColumn('name', 'string');
        $styles->addColumn('image_url', 'string');

        $styles->setPrimaryKey(['type', 'ref']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('styles');
    }
}
