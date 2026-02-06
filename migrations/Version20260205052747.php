<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205052747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE agenda (id INT AUTO_INCREMENT NOT NULL, start_time TIME NOT NULL, end_time TIME NOT NULL, category VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME NOT NULL, event_date_id INT DEFAULT NULL, INDEX IDX_2CEDC8773DC09FC4 (event_date_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8773DC09FC4 FOREIGN KEY (event_date_id) REFERENCES event_date (id)');
        $this->addSql('ALTER TABLE event_date CHANGE is_active is_active TINYINT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC8773DC09FC4');
        $this->addSql('DROP TABLE agenda');
        $this->addSql('ALTER TABLE event_date CHANGE is_active is_active TINYINT NOT NULL');
    }
}
