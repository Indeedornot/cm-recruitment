<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241003000942 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE posting (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posting_answer (id INT AUTO_INCREMENT NOT NULL, question_id INT DEFAULT NULL, posting_id INT DEFAULT NULL, user_id INT DEFAULT NULL, answer VARCHAR(255) NOT NULL, INDEX IDX_D287196A1E27F6BF (question_id), INDEX IDX_D287196A9AE985F6 (posting_id), INDEX IDX_D287196AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posting_question (id INT AUTO_INCREMENT NOT NULL, posting_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, is_enabled JSON NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_337412909AE985F6 (posting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE posting_answer ADD CONSTRAINT FK_D287196A1E27F6BF FOREIGN KEY (question_id) REFERENCES posting_question (id)');
        $this->addSql('ALTER TABLE posting_answer ADD CONSTRAINT FK_D287196A9AE985F6 FOREIGN KEY (posting_id) REFERENCES posting (id)');
        $this->addSql('ALTER TABLE posting_answer ADD CONSTRAINT FK_D287196AA76ED395 FOREIGN KEY (user_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE posting_question ADD CONSTRAINT FK_337412909AE985F6 FOREIGN KEY (posting_id) REFERENCES posting (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE posting_answer DROP FOREIGN KEY FK_D287196A1E27F6BF');
        $this->addSql('ALTER TABLE posting_answer DROP FOREIGN KEY FK_D287196A9AE985F6');
        $this->addSql('ALTER TABLE posting_answer DROP FOREIGN KEY FK_D287196AA76ED395');
        $this->addSql('ALTER TABLE posting_question DROP FOREIGN KEY FK_337412909AE985F6');
        $this->addSql('DROP TABLE posting');
        $this->addSql('DROP TABLE posting_answer');
        $this->addSql('DROP TABLE posting_question');
    }
}
