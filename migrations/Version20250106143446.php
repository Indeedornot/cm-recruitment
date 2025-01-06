<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\Dto\QuestionDto;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106143446 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $dto = new QuestionDto(
            questionKey: 'accept_terms',
            expectedType: 'bool',
            constraints: [[Assert\IsTrue::class]],
            forceSet: true,
            label: '.label',
            formType: CheckboxType::class,
            formOptions: ['required' => true, 'label_html' => true]
        );

        $questionId = $this->connection->fetchOne(
            'SELECT id FROM question WHERE question_key = :question_key',
            ['question_key' => $dto->getQuestionKey()]
        );
        if (!$questionId) {
            $this->addSql('INSERT INTO question (question_key, expected_type, constraints, force_set, is_nullable, depends_on, sort_order, label, default_value, form_type, form_options, created_at)
                               VALUES (:question_key, :expected_type, :constraints, :force_set, :is_nullable, :depends_on, :sort_order, :label, :default_value, :form_type, :form_options, NOW())',
                $dto->getInsertParams()
            );
            $this->addSql('SET @acceptTermsId = LAST_INSERT_ID()');
        } else {
            $this->addSql('SET @acceptTermsId = :question_id', ['question_id' => $questionId]);
        }
        $this->addSql('INSERT IGNORE INTO questionnaire_question (questionnaire_id, question_id) SELECT id, @acceptTermsId FROM questionnaire');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
