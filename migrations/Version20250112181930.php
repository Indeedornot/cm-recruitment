<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250112181930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update age form layout for better error handling display';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE copy_text SET form_options = REPLACE(form_options, "col-2 col-lg-1", "col-5") WHERE `key` IN ("age_min", "age_max")');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('UPDATE copy_text SET form_options = REPLACE(form_options, "col-5", "col-2 col-lg-1") WHERE `key` IN ("age_min", "age_max")');
    }
}
