<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\Dto\QuestionDto;
use App\Services\Posting\BonusCriteriaFactory;
use App\Services\Posting\QuestionService;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101090334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $questions = [
            new QuestionDto(
                questionKey: 'bonus_criteria',
                expectedType: 'array',
                constraints: [],
                forceSet: true,
                formType: ChoiceType::class,
                formOptions: [
                    'choice_factory' => [
                        'factory' => BonusCriteriaFactory::class,
                        'params' => [],
                    ],
                    'multiple' => true,
                    'expanded' => true
                ]
            )
        ];

        foreach ($questions as $dto) {
            $exists = $this->connection->fetchAssociative('SELECT * FROM question WHERE question_key = :question_key',
                ['question_key' => $dto->getQuestionKey()]);
            if ($exists) {
                continue;
            }

            $this->addSql('INSERT INTO question (question_key, expected_type, constraints, force_set, is_nullable, depends_on, sort_order, label, default_value, form_type, form_options, created_at)
                                            VALUES (:question_key, :expected_type, :constraints, :force_set, :is_nullable, :depends_on, :sort_order, :label, :default_value, :form_type, :form_options, NOW())',
                $dto->getInsertParams()
            );
        }
    }

    public function down(Schema $schema): void
    {
    }
}
