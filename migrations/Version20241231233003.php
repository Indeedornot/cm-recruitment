<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Entity\Dto\Constraint;
use App\Migration\Dto\CopyTextDto;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231233003 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $copyTexts = [
            new CopyTextDto(
                'schedule',
                [[Assert\NotBlank::class]],
                true,
                TextType::class,
                []
            ),
            new CopyTextDto(
                'age_min',
                [[Assert\GreaterThan::class, ['value' => 0]]],
                true,
                NumberType::class,
                [
                    'row_class' => ['class' => 'col-2 col-lg-1 d-inline-block']
                ]
            ),
            new CopyTextDto(
                'age_max',
                [[Assert\GreaterThan::class, ['value' => 0]]],
                true,
                NumberType::class,
                [
                    'row_attr' => ['class' => 'col-2 col-lg-1 d-inline-block ms-3']
                ]
            ),
            new CopyTextDto(
                'category',
                [[Assert\NotBlank::class]],
                true,
                ChoiceType::class,
                [
                    'choices' => [
                        'components.posting.copytext.category.theatre' => 'theatre',
                        'components.posting.copytext.category.singing' => 'singing',
                        'components.posting.copytext.category.dancing' => 'dancing',
                        'components.posting.copytext.category.sport' => 'sport',
                        'components.posting.copytext.category.film' => 'film',
                        'components.posting.copytext.category.instrument' => 'instrument',
                        'components.posting.copytext.category.photography' => 'photography',
                        'components.posting.copytext.category.creative' => 'creative',
                    ]
                ],
                label: '.label'
            )
        ];

        foreach ($copyTexts as $copyText) {
            $this->addSql(
                <<<SQL
                INSERT INTO copy_text (`key`, label, default_value, required, constraints, created_at, form_type, form_options)
                VALUES (:key, :label, :default_value, :required, :constraints, NOW(), :form_type, :form_options)
                SQL,
                [
                    'key' => $copyText->text,
                    'label' => $copyText->label,
                    'default_value' => $copyText->defaultValue,
                    'required' => (int)$copyText->required,
                    'form_type' => $copyText->formType,
                    'form_options' => json_encode($copyText->formOptions),
                    'constraints' => json_encode(Constraint::serializeArray($copyText->constraints)),
                ]
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
