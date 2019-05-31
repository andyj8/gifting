<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add style price column.
 */
class Version20150915094207 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $styles = $schema->getTable('styles');
        $styles->addColumn('price', 'decimal', [
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
        $styles = $schema->getTable('styles');
        $styles->dropColumn('prices');
    }
}
