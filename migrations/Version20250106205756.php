<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\Dto\QuestionDto;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106205756 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $questions = [
            /**
             * Jak długo grasz na instrumencie? Na jakim? //plays_instrument
             * Jak długo uprawiasz sport? Jaki? //plays_sport
             * Jak długo tańczysz? Jaki rodzaj tańca? //dances
             */

            new QuestionDto(
                questionKey: 'plays_instrument',
                expectedType: 'string',
            ),
            new QuestionDto(
                questionKey: 'plays_sport',
                expectedType: 'string',
            ),
            new QuestionDto(
                questionKey: 'dances',
                expectedType: 'string',
            ),
            new QuestionDto(
                questionKey: 'candidate_comment',
                expectedType: 'string',
                forceSet: true,
                isNullable: true,
            )
        ];

        /** @var QuestionDto $dto */
        foreach ($questions as $dto) {
            $questionId = $this->connection->fetchOne('SELECT id FROM question WHERE question_key = :question_key',
                ['question_key' => $dto->getQuestionKey()]);
            if (!$questionId) {
                $this->addSql('INSERT INTO question (question_key, expected_type, constraints, force_set, is_nullable, depends_on, sort_order, label, default_value, form_type, form_options, created_at)
                                            VALUES (:question_key, :expected_type, :constraints, :force_set, :is_nullable, :depends_on, :sort_order, :label, :default_value, :form_type, :form_options, NOW())',
                    $dto->getInsertParams()
                );
                $this->addSql(<<<SQL
                    SET @question_id = LAST_INSERT_ID()
                SQL
                );
            } else {
                $this->addSql('SET @question_id = :question_id', ['question_id' => $questionId]);
            }

            if ($dto->isForceSet()) {
                $this->addSql(
                    'INSERT IGNORE INTO questionnaire_question (questionnaire_id, question_id) SELECT id, @question_id FROM questionnaire'
                );
            }
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs=
        $this->addSql('DELETE FROM question WHERE question_key IN (\'plays_instrument\', \'plays_sport\', \'dances\', \'candidate_comment\')');
    }
}
