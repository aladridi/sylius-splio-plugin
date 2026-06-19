<?php

declare(strict_types=1);

namespace Dridialaa\SyliusSplioPlugin\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20260619185000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add Splio CRM settings table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS app_splio_settings (id INT AUTO_INCREMENT NOT NULL, base_uri VARCHAR(255) NOT NULL, auth_endpoint VARCHAR(255) NOT NULL, client_id VARCHAR(255) NOT NULL, client_secret LONGTEXT NOT NULL, timeout INT NOT NULL, enabled TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE IF EXISTS app_splio_settings');
    }
}
