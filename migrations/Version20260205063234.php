<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260205063234 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE inscription (id INT AUTO_INCREMENT NOT NULL, full_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(255) NOT NULL, institution VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY `FK_2CEDC8773DC09FC4`');
        $this->addSql('ALTER TABLE agenda CHANGE category category VARCHAR(60) NOT NULL, CHANGE title title VARCHAR(180) NOT NULL, CHANGE description description LONGTEXT DEFAULT NULL, CHANGE event_date_id event_date_id INT NOT NULL');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT FK_2CEDC8773DC09FC4 FOREIGN KEY (event_date_id) REFERENCES event_date (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE inscription');
        $this->addSql('ALTER TABLE agenda DROP FOREIGN KEY FK_2CEDC8773DC09FC4');
        $this->addSql('ALTER TABLE agenda CHANGE category category VARCHAR(255) NOT NULL, CHANGE title title VARCHAR(255) NOT NULL, CHANGE description description LONGTEXT NOT NULL, CHANGE event_date_id event_date_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE agenda ADD CONSTRAINT `FK_2CEDC8773DC09FC4` FOREIGN KEY (event_date_id) REFERENCES event_date (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
