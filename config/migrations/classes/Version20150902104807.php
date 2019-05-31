<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add gifts table with basic columns
 */
class Version20150902104807 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gifts = $schema->createTable('gifts');
        $gifts->addColumn('id', 'integer',
            ['unsigned' => true, 'autoincrement'=>true]);
        $gifts->addColumn('type', 'string');
        $gifts->addColumn('voucher_code', 'string');
        $gifts->addColumn('voucher_expiry', 'date');

        $gifts->setPrimaryKey(['id']);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $schema->dropTable('gifts');
    }
}
