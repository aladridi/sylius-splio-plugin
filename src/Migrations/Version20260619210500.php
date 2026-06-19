<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260619210500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Splio product API endpoint setting';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("ALTER TABLE app_splio_product_sync_settings ADD product_endpoint VARCHAR(255) DEFAULT '/products' NOT NULL AFTER frequency");
        $this->addSql('ALTER TABLE app_splio_product_sync_settings ALTER product_endpoint DROP DEFAULT');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE app_splio_product_sync_settings DROP product_endpoint');
    }
}
