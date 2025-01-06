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
        $this->addSql('ALTER TABLE question CHANGE form_options form_options JSON DEFAULT \'{}\' NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('UPDATE bonus_criteria SET value = \'{}\' WHERE value = \'\' OR value IS NULL');
        $this->addSql('UPDATE copy_text SET constraints = \'[]\' WHERE constraints = \'\' OR constraints IS NULL');
        $this->addSql('UPDATE copy_text SET form_options = \'{}\' WHERE form_options = \'\' OR form_options IS NULL');
        $this->addSql('UPDATE email_report SET recipients = \'[]\' WHERE recipients = \'\' OR recipients IS NULL');
        $this->addSql('UPDATE email_report SET recipient_ids = \'[]\' WHERE recipient_ids = \'\' OR recipient_ids IS NULL');
        $this->addSql('UPDATE global_config SET form_options = \'{}\' WHERE form_options = \'\' OR form_options IS NULL');
        $this->addSql('UPDATE global_config SET constraints = \'[]\' WHERE constraints = \'\' OR constraints IS NULL');
        $this->addSql('UPDATE question SET form_options = \'{}\' WHERE form_options = \'\' OR form_options IS NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE global_config CHANGE form_options form_options JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE constraints constraints JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE bonus_criteria CHANGE value value JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE email_report CHANGE recipients recipients JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE recipient_ids recipient_ids JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE copy_text CHANGE constraints constraints JSON NOT NULL COMMENT \'(DC2Type:json)\', CHANGE form_options form_options JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE question CHANGE form_options form_options JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }
}
