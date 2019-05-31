<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Add additional columns to gifts table
 */
class Version20150902113531 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->addColumn('sender', 'json_array');
        $gifts->addColumn('recipient', 'json_array');
        $gifts->addColumn('product', 'json_array');
        $gifts->addColumn('delivery_date', 'date');
        $gifts->addColumn('message', 'text');
        $gifts->addColumn('style_ref', 'string');
        $gifts->addColumn('delivered', 'boolean');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        $gifts = $schema->getTable('gifts');
        $gifts->dropColumn('sender');
        $gifts->dropColumn('recipient');
        $gifts->dropColumn('product');
        $gifts->dropColumn('delivery_date');
        $gifts->dropColumn('message');
        $gifts->dropColumn('style_ref');
        $gifts->dropColumn('delivered');
    }
}
