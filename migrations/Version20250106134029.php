<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106134029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bonus_criteria CHANGE value value JSON DEFAULT \'{}\' NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE copy_text CHANGE constraints constraints JSON DEFAULT \'[]\' NOT NULL COMMENT \'(DC2Type:json)\', CHANGE form_options form_options JSON DEFAULT \'{}\' NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE email_report CHANGE recipients recipients JSON DEFAULT \'[]\' NOT NULL COMMENT \'(DC2Type:json)\', CHANGE recipient_ids recipient_ids JSON DEFAULT \'[]\' NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE global_config CHANGE form_options form_options JSON DEFAULT \'{}\' NOT NULL COMMENT \'(DC2Type:json)\', CHANGE constraints constraints JSON DEFAULT \'[]\' NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE question DROP additional_data, CHANGE form_options form_options JSON DEFAULT \'{}\' NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question ADD additional_data JSON DEFAULT NULL COMMENT \'(DC2Type:json)\', CHANGE form_options form_options JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE global_config CHANGE form_options form_options JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE constraints constraints JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE bonus_criteria CHANGE value value JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE email_report CHANGE recipients recipients JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE recipient_ids recipient_ids JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE copy_text CHANGE constraints constraints JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE form_options form_options JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
