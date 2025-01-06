<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Migration\Dto\CopyTextDto;
use App\Migration\Dto\QuestionDto;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101232522 extends AbstractMigration
{
    /** @var CopyTextDto[] */
    protected array $copyTexts = [];

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->copyTexts = [
            new CopyTextDto(
                text: 'limit',
                constraints: [[Assert\GreaterThanOrEqual::class, ['value' => 1]]],
                required: false,
                formType: NumberType::class,
                formOptions: [
                    'attr' => ['min' => 1]
                ],
                defaultValue: 1
            )
        ];
        parent::__construct($connection, $logger);
    }

    public function getDescription(): string
    {
        return 'Add Candidate limit field';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        foreach ($this->copyTexts as $copyText) {
            $exists = $this->connection->fetchAssociative('SELECT * FROM copy_text WHERE `key` = :text',
                ['text' => $copyText->getText()]);

            if ($exists) {
                continue;
            }

            $this->addSql('
                INSERT INTO cm_symfony.copy_text
                    (`key`, label, default_value, required, `constraints`, form_type, form_options, created_at)
                VALUES
                    (:key, :label, :default_value, :required, :constraints, :form_type, :form_options, NOW()
                )',
                $copyText->getInsertParams()
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $texts = array_map(fn(CopyTextDto $dto) => $dto->getText(), $this->copyTexts);
        $this->addSql('DELETE FROM copy_text WHERE `key` IN (:texts)', ['texts' => $texts]);
    }
}
