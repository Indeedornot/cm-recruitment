<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106103127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bonus_criteria (
                id INT AUTO_INCREMENT NOT NULL,
                label VARCHAR(255) NOT NULL,
                `key` VARCHAR(255) NOT NULL,
                value JSON NOT NULL COMMENT \'(DC2Type:json)\',
                created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                disabled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\',
                sort_order INT NOT NULL DEFAULT 0,
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`
            ENGINE = InnoDB
        ');
        $arr = [
            'additional_docs' => [
                'value' => [
                    'first_phase' => 0,
                    'second_phase' => 0,
                ],
                'sort_order' => 99,
            ],
            'big_family' => [
                'value' => [
                    'first_phase' => 0,
                ]
            ],
            'disability' => [
                'value' => [
                    'first_phase' => 0,
                ]
            ],
            'disabled_parent' => [
                'value' => [
                    'first_phase' => 0,
                ]
            ],
            'disabled_parents' => [
                'value' => [
                    'first_phase' => 0,
                ]
            ],
            'disabled_sibling' => [
                'value' => [
                    'first_phase' => 0,
                ]
            ],
            'one_parent' => [
                'value' => [
                    'first_phase' => 0,
                ]
            ],
            'foster_family' => [
                'value' => [
                    'first_phase' => 0,
                ]
            ],
            'big_krakow_family' => [
                'value' => [
                    'second_phase' => 15,
                ]
            ],
            'curatorship' => [
                'value' => [
                    'second_phase' => 10,
                ]
            ],
            'both_working_parents' => [
                'value' => [
                    'second_phase' => 10,
                ]
            ],
            'related_achievement' => [
                'value' => [
                    'second_phase' => 5,
                ]
            ],
            'volunteer' => [
                'value' => [
                    'second_phase' => 5,
                ]
            ],
            'closest_facility' => [
                'value' => [
                    'second_phase' => 5,
                ]
            ],
        ];

        foreach ($arr as $key => $value) {
            $this->addSql('INSERT INTO bonus_criteria (label, `key`, value, sort_order, created_at) VALUES (:label, :key, :value, :sort_order, NOW())',
                [
                    'label' => "components.question.criteria.$key",
                    'key' => $key,
                    'value' => json_encode($value['value']),
                    'sort_order' => $value['sort_order'] ?? 0,
                ]);
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bonus_criteria');
    }
}
