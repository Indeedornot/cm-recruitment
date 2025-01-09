<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250108215211 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_application ADD sub_posting_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE client_application ADD CONSTRAINT FK_A510F8FAB622163B FOREIGN KEY (sub_posting_id) REFERENCES sub_posting (id)');
        $this->addSql('CREATE INDEX IDX_A510F8FAB622163B ON client_application (sub_posting_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client_application DROP FOREIGN KEY FK_A510F8FAB622163B');
        $this->addSql('DROP INDEX IDX_A510F8FAB622163B ON client_application');
        $this->addSql('ALTER TABLE client_application DROP sub_posting_id');
    }
}
