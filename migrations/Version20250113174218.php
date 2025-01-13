<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Services\Posting\APDepFactory;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250113174218 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Application Phase Questions';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $factory = [
            'factory' => [
                'factory' => APDepFactory::class,
                'params' => [
                    'applicationPhase' => [
                        'first_phase',
                        'second_phase',
                    ],
                ],
            ]
        ];

        $this->addSql('
            UPDATE question
            SET form_options = :factory
            WHERE question_key IN ("plays_instrument", "plays_sport", "dances")
        ',
            ['factory' => json_encode($factory)]
        );

        //update fields where they have in json choice_factory to factory
        $this->addSql('
            UPDATE question
            SET form_options = \'{"factory":{"factory":"App\\\\\\\\Services\\\\\\\\Posting\\\\\\\\BonusCriteriaFactory","params":[]},"multiple":true,"expanded":true}\'
            WHERE question_key = "bonus_criteria"
        ');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
