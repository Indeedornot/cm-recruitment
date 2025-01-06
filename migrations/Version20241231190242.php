<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\Dto\QuestionDto;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231190242 extends AbstractMigration
{
    private array $questionsToDisable = [
        'city',
        'street',
        'house_no',
        'postal_code'
    ];

    private string $newQuestionKey = 'address';

    public function getDescription(): string
    {
        return 'Migrates to one address question instead of multiple';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $newQuestion = $this->connection->fetchAssociative(
            'SELECT * FROM question WHERE `question_key` = :key',
            ['key' => $this->newQuestionKey]
        );

        if (empty($newQuestion)) {
            $dto = new QuestionDto(
                $this->newQuestionKey,
                'string',
                [[Assert\NotBlank::class]],
                false
            );

            $this->addSql(
                <<<SQL
                INSERT INTO question (question_key, expected_type, constraints, is_nullable, label, force_set, depends_on, sort_order, created_at)
                VALUES (:question_key, :expected_type, :constraints, :is_nullable, :label, :force_set, :depends_on, :sort_order, NOW())
                SQL,
                $dto->getInsertParams()
            );
        }

        foreach ($this->questionsToDisable as $questionKey) {
            $this->addSql(
                <<<SQL
                UPDATE question SET disabled_at = NOW() WHERE `question_key` = :key AND disabled_at IS NULL
                SQL,
                ['key' => $questionKey]
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(
            <<<SQL
            UPDATE question SET disabled_at = NOW() WHERE `question_key` = :key AND disabled_at IS NULL
            SQL,
            ['key' => $this->newQuestionKey]
        );

        foreach ($this->questionsToDisable as $questionKey) {
            $this->addSql(
                <<<SQL
                UPDATE question SET disabled_at = NULL WHERE `question_key` = :key
                SQL,
                ['key' => $questionKey]
            );
        }
    }
}
