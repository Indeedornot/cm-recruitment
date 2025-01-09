<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109170604 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE schedule (id INT AUTO_INCREMENT NOT NULL, posting_id INT DEFAULT NULL, time VARCHAR(255) NOT NULL, person_limit INT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_5A3811FB9AE985F6 (posting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE schedule ADD CONSTRAINT FK_5A3811FB9AE985F6 FOREIGN KEY (posting_id) REFERENCES posting (id)');
        $this->addSql('ALTER TABLE client_application ADD schedule_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_application ADD CONSTRAINT FK_A510F8FAA40BC2D5 FOREIGN KEY (schedule_id) REFERENCES schedule (id)');
        $this->addSql('CREATE INDEX IDX_A510F8FAA40BC2D5 ON client_application (schedule_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_application DROP FOREIGN KEY FK_A510F8FAA40BC2D5');
        $this->addSql('ALTER TABLE schedule DROP FOREIGN KEY FK_5A3811FB9AE985F6');
        $this->addSql('DROP TABLE schedule');
        $this->addSql('DROP INDEX IDX_A510F8FAA40BC2D5 ON client_application');
        $this->addSql('ALTER TABLE client_application DROP schedule_id');
    }
}
