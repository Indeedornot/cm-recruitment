<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231232957 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<SQL
            CREATE TABLE copy_text (
                id INT AUTO_INCREMENT NOT NULL,
                `key` LONGTEXT NOT NULL,
                label VARCHAR(255) NOT NULL,
                default_value JSON DEFAULT NULL COMMENT '(DC2Type:json)',
                required TINYINT(1) NOT NULL,
                constraints JSON NOT NULL COMMENT '(DC2Type:json)',
                form_type LONGTEXT NOT NULL,
                form_options JSON NOT NULL COMMENT '(DC2Type:json)',
                disabled_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
                created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)',
                updated_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)',
                UNIQUE INDEX UNIQ_EAE5B73B8A90ABA9 (`key`),
                PRIMARY KEY(id)
            )
            DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
            SQL
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE copy_text');
    }
}
