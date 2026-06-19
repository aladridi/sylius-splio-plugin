<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260619201500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Splio product synchronization settings table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app_splio_product_sync_settings (id INT AUTO_INCREMENT NOT NULL, enabled TINYINT(1) NOT NULL, sync_mode VARCHAR(32) NOT NULL, frequency VARCHAR(32) NOT NULL, batch_size INT NOT NULL, include_disabled_products TINYINT(1) NOT NULL, include_variants TINYINT(1) NOT NULL, sync_images TINYINT(1) NOT NULL, sync_prices TINYINT(1) NOT NULL, last_sync_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE app_splio_product_sync_settings');
    }
}
