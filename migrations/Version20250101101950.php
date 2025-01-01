<?php

namespace DoctrineMigrations;

use App\Migration\Dto\GlobalConfigDto;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

final class Version20250101101950 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Creates initial global configuration settings';
    }

    public function up(Schema $schema): void
    {
        $configs = [
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
                label: '.label',
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
                ]
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

        foreach ($configs as $config) {
            $this->addSql(
                'INSERT INTO global_config (`key`, label, value, form_type, form_options, constraints)
                    VALUES (:key, :label, :value, :form_type, :form_options, :constraints)',
                $config->getInsertParams()
            );
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DELETE FROM global_config WHERE `key` IN ("application_phase", "closing_date")');
    }
}
