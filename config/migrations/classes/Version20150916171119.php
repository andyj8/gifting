<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add gift price column.
 */
class Version20150916171119 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->addColumn('price', 'decimal', [
            'precision' => 12,
            'scale' => 2,
            'default' => 0.00
        ]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->dropColumn('price');
    }
}
