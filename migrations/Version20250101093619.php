<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101093619 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question CHANGE form_type form_type VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE questionnaire_answer SET answer = JSON_QUOTE(answer) WHERE JSON_VALID(answer) = 0');
        $this->addSql('ALTER TABLE questionnaire_answer CHANGE answer answer JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE questionnaire_answer CHANGE answer answer VARCHAR(255) NOT NULL');
        $this->addSql('UPDATE questionnaire_answer SET answer = JSON_UNQUOTE(JSON_EXTRACT(answer, \'$[0]\')) WHERE JSON_VALID(answer) = 1');
    }
}
