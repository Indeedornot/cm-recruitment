<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923053356 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76B03A8386 FOREIGN KEY (created_by_id) REFERENCES `admin` (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_880E0D76B03A8386 ON admin (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `admin` DROP FOREIGN KEY FK_880E0D76B03A8386');
        $this->addSql('DROP INDEX UNIQ_880E0D76B03A8386 ON `admin`');
        $this->addSql('ALTER TABLE `admin` DROP created_by_id');
    }
}
