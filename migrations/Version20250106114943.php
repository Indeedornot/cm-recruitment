<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106114943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE client_application SET data = \'{}\' WHERE data IS NULL OR data = \'\'');
        $this->addSql('ALTER TABLE client_application CHANGE data data JSON DEFAULT \'{}\' NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_application CHANGE data data JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('UPDATE client_application SET data = NULL WHERE data = \'{}\'');
    }
}
