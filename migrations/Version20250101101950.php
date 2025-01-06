<?php

namespace DoctrineMigrations;

use App\Migration\Dto\GlobalConfigDto;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Psr\Log\LoggerInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

final class Version20250101101950 extends AbstractMigration
{
    private array $configs = [];

    public function __construct(Connection $connection, LoggerInterface $logger)
    {
        $this->configs = [
//            new GlobalConfigDto(
//                key: 'site_name',
//                defaultValue: 'My Site',
//                formType: TextType::class,
//                constraints: [
//                    [Assert\NotBlank::class],
//                    [Assert\Length::class, (['min' => 3, 'max' => 50])]
//                ]
//            ),
//            new GlobalConfigDto(
//                key: 'admin_email',
//                defaultValue: 'admin@example.com',
//                formType: EmailType::class,
//                constraints: [
//                    [Assert\NotBlank::class],
//                    [Assert\Email::class]
//                ],
//            )
            new GlobalConfigDto(
                key: 'application_phase',
                defaultValue: 'continuation',
                formType: ChoiceType::class,
                formOptions: [
                    'choices' => [
                        'components.global_config.application_phase.continuation' => 'continuation',
                        'components.global_config.application_phase.first_phase' => 'first_phase',
                        'components.global_config.application_phase.second_phase' => 'second_phase'
                    ],
                    'help' => 'components.global_config.application_phase.help',
                    'help_html' => true
                ],
                label: '.label'
            ),
            new GlobalConfigDto(
                key: 'closing_date',
                defaultValue: null,
                formType: DateTimeType::class,
                formOptions: [
                    'attr' => [
                        'class' => 'datetimepicker'
                    ],
                    'help' => 'components.global_config.closing_date.help',
                    'help_html' => true,
                    'required' => false
                ],
                constraints: [
                    [Assert\DateTime::class]
                ],
                label: '.label'
            )
        ];
        parent::__construct($connection, $logger);
    }

    public function getDescription(): string
    {
        return 'Creates initial global configuration settings';
    }

    public function up(Schema $schema): void
    {
        foreach ($this->configs as $config) {
            $exists = $this->connection->fetchAssociative('SELECT * FROM global_config WHERE `key` = :key',
                ['key' => $config->getKey()]);
            if ($exists) {
                continue;
            }

            $this->addSql(
                'INSERT INTO global_config (`key`, label, value, form_type, form_options, constraints)
                    VALUES (:key, :label, :value, :form_type, :form_options, :constraints)',
                $config->getInsertParams()
            );
        }
    }

    public function down(Schema $schema): void
    {
        $keys = array_map(fn(GlobalConfigDto $dto) => $dto->getKey(), $this->configs);
        $this->addSql('DELETE FROM global_config WHERE `key` IN (:keys)', ['keys' => $keys]);
    }
}
