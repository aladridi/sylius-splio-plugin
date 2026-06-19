<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260619212000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Splio product synchronization logs';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE app_splio_product_sync_log (id INT AUTO_INCREMENT NOT NULL, product_code VARCHAR(255) NOT NULL, status VARCHAR(32) NOT NULL, endpoint VARCHAR(255) NOT NULL, payload JSON NOT NULL, response JSON DEFAULT NULL, error_message LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_SPLIO_PRODUCT_SYNC_LOG_STATUS (status), INDEX IDX_SPLIO_PRODUCT_SYNC_LOG_PRODUCT_CODE (product_code), INDEX IDX_SPLIO_PRODUCT_SYNC_LOG_CREATED_AT (created_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE app_splio_product_sync_log');
    }
}
