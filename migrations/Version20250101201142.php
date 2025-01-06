<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101201142 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Update default closing date';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $exists = $this->connection->fetchAssociative('SELECT * FROM global_config WHERE `key` = :key',
            ['key' => 'closing_date']);

        if ($exists) {
            $this->addSql(<<<SQL
                UPDATE global_config
                SET value = JSON_QUOTE('2025-06-21 23:59:59')
                WHERE `key` = 'closing_date'
            SQL
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
    }
}
