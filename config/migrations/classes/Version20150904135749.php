<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Nullify delivery date to allow immeditate delivery.
 */
class Version20150904135749 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->changeColumn('delivery_date', [
            'NotNull' => false
        ]);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->changeColumn('delivery_date', [
            'NotNull' => true
        ]);
    }
}
