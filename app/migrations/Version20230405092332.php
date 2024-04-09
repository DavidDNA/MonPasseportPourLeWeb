<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230405092332 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar_upgrade CHANGE priority priority INT NOT NULL');
        $this->addSql('ALTER TABLE session ADD permain_color VARCHAR(255) NOT NULL default "#535e85", ADD pertext_color VARCHAR(255) NOT NULL default "#f4f4f4"');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session DROP permain_color, DROP pertext_color');
        $this->addSql('ALTER TABLE avatar_upgrade CHANGE priority priority INT DEFAULT 0 NOT NULL');
    }
}
