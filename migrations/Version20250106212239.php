<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250106212239 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $keys = [
            'first_name',
            'last_name',
            'email',
            'phone',
            'age',
            'pesel',
            'city',
            'street',
            'house_no',
            'postal_code',
            'address',
        ];

        $maxKeys = [
            'bonus_criteria',
            'accept_terms'
        ];

        foreach ($keys as $idx => $key) {
            $this->addSql('
                UPDATE question
                SET sort_order = :sort_order
                WHERE question_key = :question_key
            ', ['sort_order' => $idx - 100, 'question_key' => $key]);
        }

        foreach ($maxKeys as $idx => $key) {
            $this->addSql('
                UPDATE question
                SET sort_order = :sort_order
                WHERE question_key = :question_key
            ', ['sort_order' => 100 + $idx, 'question_key' => $key]);
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
