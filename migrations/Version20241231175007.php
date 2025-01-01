<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use App\Contract\PhoneNumber\Validator\PhoneNumber;
use App\Migration\Dto\QuestionDto;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241231175007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE admin (id INT NOT NULL, created_by_id INT DEFAULT NULL, INDEX IDX_880E0D76B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client (id INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE client_application (id INT AUTO_INCREMENT NOT NULL, client_id INT DEFAULT NULL, posting_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', disabled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A510F8FA19EB6921 (client_id), INDEX IDX_A510F8FA9AE985F6 (posting_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE email_report (id INT AUTO_INCREMENT NOT NULL, created_by_id INT DEFAULT NULL, subject VARCHAR(255) NOT NULL, body LONGTEXT NOT NULL, recipients JSON NOT NULL COMMENT \'(DC2Type:json)\', recipient_ids JSON NOT NULL COMMENT \'(DC2Type:json)\', sent_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_B9B5A474B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posting (id INT AUTO_INCREMENT NOT NULL, assigned_to_id INT DEFAULT NULL, questionnaire_id INT DEFAULT NULL, created_by_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, closing_date DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', disabled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BD275D73F4BD7827 (assigned_to_id), INDEX IDX_BD275D73CE07E8FF (questionnaire_id), INDEX IDX_BD275D73B03A8386 (created_by_id), UNIQUE INDEX UNIQ_IDENTIFIER_TITLE (title), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question_key VARCHAR(255) NOT NULL, expected_type VARCHAR(255) NOT NULL, default_value VARCHAR(255) DEFAULT NULL, is_nullable TINYINT(1) NOT NULL, label VARCHAR(255) NOT NULL, constraints JSON NOT NULL COMMENT \'(DC2Type:json)\', force_set TINYINT(1) NOT NULL, depends_on JSON NOT NULL COMMENT \'(DC2Type:json)\', sort_order INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire_question (questionnaire_id INT NOT NULL, question_id INT NOT NULL, INDEX IDX_28CC40C3CE07E8FF (questionnaire_id), INDEX IDX_28CC40C31E27F6BF (question_id), PRIMARY KEY(questionnaire_id, question_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE questionnaire_answer (id INT AUTO_INCREMENT NOT NULL, application_id INT DEFAULT NULL, question_id INT DEFAULT NULL, answer VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_437B451C3E030ACD (application_id), INDEX IDX_437B451C1E27F6BF (question_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, last_password_change DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', disabled_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76B03A8386 FOREIGN KEY (created_by_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE admin ADD CONSTRAINT FK_880E0D76BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C7440455BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE client_application ADD CONSTRAINT FK_A510F8FA19EB6921 FOREIGN KEY (client_id) REFERENCES client (id)');
        $this->addSql('ALTER TABLE client_application ADD CONSTRAINT FK_A510F8FA9AE985F6 FOREIGN KEY (posting_id) REFERENCES posting (id)');
        $this->addSql('ALTER TABLE email_report ADD CONSTRAINT FK_B9B5A474B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE posting ADD CONSTRAINT FK_BD275D73F4BD7827 FOREIGN KEY (assigned_to_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE posting ADD CONSTRAINT FK_BD275D73CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id)');
        $this->addSql('ALTER TABLE posting ADD CONSTRAINT FK_BD275D73B03A8386 FOREIGN KEY (created_by_id) REFERENCES admin (id)');
        $this->addSql('ALTER TABLE questionnaire_question ADD CONSTRAINT FK_28CC40C3CE07E8FF FOREIGN KEY (questionnaire_id) REFERENCES questionnaire (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaire_question ADD CONSTRAINT FK_28CC40C31E27F6BF FOREIGN KEY (question_id) REFERENCES question (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE questionnaire_answer ADD CONSTRAINT FK_437B451C3E030ACD FOREIGN KEY (application_id) REFERENCES client_application (id)');
        $this->addSql('ALTER TABLE questionnaire_answer ADD CONSTRAINT FK_437B451C1E27F6BF FOREIGN KEY (question_id) REFERENCES question (id)');

        /* @var QuestionDto[] $questions */
        $questions = [
            new QuestionDto(
                'first_name',
                'string',
                [[Assert\Length::class, ['min' => 2, 'max' => 100]]],
                true,
                dependsOn: ['last_name']
            ),
            new QuestionDto(
                'last_name',
                'string',
                [[Assert\Length::class, ['min' => 2, 'max' => 100]]],
                true,
                dependsOn: ['first_name']
            ),
            new QuestionDto(
                'email',
                'string',
                [[Assert\Email::class]],
                true
            ),
            new QuestionDto(
                'phone',
                PhoneNumber::class,
                [[PhoneNumber::class, (['defaultRegion' => 'PL', 'type' => PhoneNumber::MOBILE])]],
            ),
            new QuestionDto(
                'age',
                'integer',
                [[Assert\Range::class, (['min' => 0, 'max' => 100])]],
                true
            ),
            new QuestionDto(
                'pesel',
                'string',
                [[Assert\Length::class, (['value' => 11])]],
                true
            ),
            new QuestionDto(
                'city',
                'string',
                [[Assert\Length::class, (['min' => 2, 'max' => 100])]],
            ),
            new QuestionDto(
                'street',
                'string',
                [[Assert\Length::class, (['min' => 1, 'max' => 100])], [Assert\IsNull::class]],
            ),
            new QuestionDto(
                'house_no',
                'string',
                [[Assert\Length::class, (['min' => 1, 'max' => 6])]],
                dependsOn: ['street', 'city']
            ),
            new QuestionDto(
                'postal_code',
                'string',
                [[Assert\Regex::class, '/^\d{2}-\d{3}$/']],
                dependsOn: ['city']
            ),
        ];

        foreach ($questions as $question) {
            $this->addSql(
                <<<SQL
                INSERT INTO question (question_key, expected_type, constraints, is_nullable, label, force_set, depends_on, sort_order, created_at)
                VALUES (:question_key, :expected_type, :constraints, :is_nullable, :label, :force_set, :depends_on, :sort_order, NOW())
                SQL,
                $question->getInsertParams()
            );
        }
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76B03A8386');
        $this->addSql('ALTER TABLE admin DROP FOREIGN KEY FK_880E0D76BF396750');
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C7440455BF396750');
        $this->addSql('ALTER TABLE client_application DROP FOREIGN KEY FK_A510F8FA19EB6921');
        $this->addSql('ALTER TABLE client_application DROP FOREIGN KEY FK_A510F8FA9AE985F6');
        $this->addSql('ALTER TABLE email_report DROP FOREIGN KEY FK_B9B5A474B03A8386');
        $this->addSql('ALTER TABLE posting DROP FOREIGN KEY FK_BD275D73F4BD7827');
        $this->addSql('ALTER TABLE posting DROP FOREIGN KEY FK_BD275D73CE07E8FF');
        $this->addSql('ALTER TABLE posting DROP FOREIGN KEY FK_BD275D73B03A8386');
        $this->addSql('ALTER TABLE questionnaire_question DROP FOREIGN KEY FK_28CC40C3CE07E8FF');
        $this->addSql('ALTER TABLE questionnaire_question DROP FOREIGN KEY FK_28CC40C31E27F6BF');
        $this->addSql('ALTER TABLE questionnaire_answer DROP FOREIGN KEY FK_437B451C3E030ACD');
        $this->addSql('ALTER TABLE questionnaire_answer DROP FOREIGN KEY FK_437B451C1E27F6BF');
        $this->addSql('DROP TABLE admin');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE client_application');
        $this->addSql('DROP TABLE email_report');
        $this->addSql('DROP TABLE posting');
        $this->addSql('DROP TABLE question');
        $this->addSql('DROP TABLE questionnaire');
        $this->addSql('DROP TABLE questionnaire_question');
        $this->addSql('DROP TABLE questionnaire_answer');
        $this->addSql('DROP TABLE user');
    }
}
