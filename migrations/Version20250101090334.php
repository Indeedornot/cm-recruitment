<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\Dto\QuestionDto;
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
        $this->addSql('ALTER TABLE question ADD additional_data JSON DEFAULT NULL');

        $questions = [
            new QuestionDto(
                questionKey: 'bonus_criteria',
                expectedType: 'array',
                constraints: [],
                label: 'Kryteria dodatkowe',
                formType: ChoiceType::class,
                formOptions: [
                    'choices' => [
                        'Krakowska Karta Rodzinna 3+' => 'kk3plus',
                        'Nadzór kuratorski/wsparcie asystenta rodziny' => 'curator',
                        'Oboje rodziców pracujących/studiujących' => 'working_parents',
                        'Udokumentowane osiągnięcia' => 'achievements',
                        'Działalność społeczna/wolontariat' => 'volunteer',
                        'Najbliższa placówka' => 'closest',
                        'Jestem świadomy, że komisja może wymagać dodatkowych dokumentów' => 'additional_docs'
                    ],
                    'multiple' => true,
                    'expanded' => true
                ],
//                TODO: Remove
                additionalData: [
                    'points' => [
                        'kk3plus' => 15,
                        'curator' => 10,
                        'working_parents' => 10,
                        'achievements' => 5,
                        'volunteer' => 5,
                        'closest' => 5
                    ]
                ],
                forceSet: true
            )
        ];

        foreach ($questions as $dto) {
            $exists = $this->connection->fetchAssociative('SELECT * FROM question WHERE question_key = :question_key',
                ['question_key' => $dto->getQuestionKey()]);
            if ($exists) {
                continue;
            }

            $this->addSql('INSERT INTO question (question_key, expected_type, constraints, force_set, is_nullable, depends_on, sort_order, label, default_value, form_type, form_options, additional_data, created_at) VALUES (:question_key, :expected_type, :constraints, :force_set, :is_nullable, :depends_on, :sort_order, :label, :default_value, :form_type, :form_options, :additional_data, NOW())',
                $dto->getInsertParams()
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP additional_data');
    }
}
