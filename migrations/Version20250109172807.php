<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250109172807 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $postingIds = $this->connection->fetchFirstColumn('SELECT id FROM posting');
        $scheduleCopyTextId = $this->connection->fetchOne('
                SELECT id
                FROM copy_text
                WHERE `key` = :key
            ', [
            'key' => 'schedule',
        ]);
        $limitCopyTextId = $this->connection->fetchOne('
                SELECT id
                FROM copy_text
                WHERE `key` = :key
            ', [
            'key' => 'limit',
        ]);
        foreach ($postingIds as $postingId) {
            $schedule = $this->connection->fetchOne('
                SELECT value
                FROM posting_text
                WHERE posting_id = :posting_id AND posting_text.copy_text_id = :copy_text_id
            ', [
                'posting_id' => $postingId,
                'copy_text_id' => $scheduleCopyTextId,
            ]);

            $limit = $this->connection->fetchOne('
                SELECT value
                FROM posting_text
                WHERE posting_id = :posting_id AND posting_text.copy_text_id = :copy_text_id
            ', [
                'posting_id' => $postingId,
                'copy_text_id' => $limitCopyTextId,
            ]);

            $this->addSql('INSERT INTO schedule (posting_id, time, person_limit) VALUES (:posting_id, :time, :person_limit)',
                [
                    'posting_id' => $postingId,
                    'time' => $schedule,
                    'person_limit' => intval($limit),
                ]);
        }

        $this->addSql('UPDATE copy_text SET disabled_at = NOW() WHERE `key` = :key', ['key' => 'schedule']);
        $this->addSql('UPDATE copy_text SET disabled_at = NOW() WHERE `key` = :key', ['key' => 'limit']);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
