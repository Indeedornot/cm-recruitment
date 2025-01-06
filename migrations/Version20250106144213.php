<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106144213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $questionIds = $this->connection->fetchFirstColumn(<<<SQL
                SELECT id
                FROM question
                WHERE question_key IN ('phone', 'accept_terms', 'bonus_criteria')
            SQL
        );
        foreach ($questionIds as $questionId) {
            $this->addSql(
                'INSERT IGNORE INTO questionnaire_question (questionnaire_id, question_id) SELECT id, :questionId FROM questionnaire',
                ['questionId' => $questionId]
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
