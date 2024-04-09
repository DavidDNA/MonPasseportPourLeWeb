<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405094048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session ADD perobjectives LONGTEXT DEFAULT NULL, CHANGE permain_color permain_color VARCHAR(255) NOT NULL, CHANGE pertext_color pertext_color VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP perobjectives, CHANGE permain_color permain_color VARCHAR(255) DEFAULT \'#535e85\' NOT NULL, CHANGE pertext_color pertext_color VARCHAR(255) DEFAULT \'#f4f4f4\' NOT NULL');
    }
}
