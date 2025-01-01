<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101003101 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posting_text (id INT AUTO_INCREMENT NOT NULL, posting_id INT DEFAULT NULL, copy_text_id INT DEFAULT NULL, value LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8DD21A2C9AE985F6 (posting_id), INDEX IDX_8DD21A2C359BE123 (copy_text_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posting_text ADD CONSTRAINT FK_8DD21A2C9AE985F6 FOREIGN KEY (posting_id) REFERENCES posting (id)');
        $this->addSql('ALTER TABLE posting_text ADD CONSTRAINT FK_8DD21A2C359BE123 FOREIGN KEY (copy_text_id) REFERENCES copy_text (id)');
        $this->addSql('ALTER TABLE copy_text CHANGE default_value default_value JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posting_text DROP FOREIGN KEY FK_8DD21A2C9AE985F6');
        $this->addSql('ALTER TABLE posting_text DROP FOREIGN KEY FK_8DD21A2C359BE123');
        $this->addSql('DROP TABLE posting_text');
        $this->addSql('ALTER TABLE copy_text CHANGE default_value default_value JSON DEFAULT NULL COMMENT \'(DC2Type:json)\'');
    }
}
