<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230124092008 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE session_activity (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT DEFAULT NULL, resources LONGTEXT DEFAULT NULL, INDEX IDX_27D86932613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE session_activity ADD CONSTRAINT FK_27D86932613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE session ADD name VARCHAR(255) NOT NULL, ADD objectives LONGTEXT DEFAULT NULL, ADD material LONGTEXT DEFAULT NULL, ADD teacher_indications LONGTEXT DEFAULT NULL, ADD summary LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session_activity DROP FOREIGN KEY FK_27D86932613FECDF');
        $this->addSql('DROP TABLE session_activity');
        $this->addSql('ALTER TABLE session DROP name, DROP objectives, DROP material, DROP teacher_indications, DROP summary');
    }
}
