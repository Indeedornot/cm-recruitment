<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Form\Extension\Core\Type\TextType;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101084137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $defaultFormType = TextType::class;
        $this->addSql(<<<SQL
            ALTER TABLE question ADD form_type VARCHAR(255) NOT NULL DEFAULT :default, ADD form_options JSON NOT NULL COMMENT '(DC2Type:json)'
        SQL,
            ['default' => $defaultFormType]
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE question DROP form_type, DROP form_options');
    }
}
