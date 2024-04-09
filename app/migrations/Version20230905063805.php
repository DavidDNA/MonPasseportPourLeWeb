<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230905063805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar_upgrade DROP relative_threshold, CHANGE type type VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE year_group ADD avatar_upgrades INT NOT NULL DEFAULT 6');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar_upgrade ADD relative_threshold DOUBLE PRECISION NOT NULL, CHANGE type type VARCHAR(255) DEFAULT \'sprite\' NOT NULL');
        $this->addSql('ALTER TABLE year_group DROP avatar_upgrades');
    }
}
