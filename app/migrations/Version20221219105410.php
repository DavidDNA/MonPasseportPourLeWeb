<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219105410 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar ADD student_id INT NOT NULL');
        $this->addSql('ALTER TABLE avatar ADD CONSTRAINT FK_1677722FCB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1677722FCB944F1A ON avatar (student_id)');
        $this->addSql('ALTER TABLE avatar_has_upgrades ADD avatar_id INT NOT NULL, ADD upgrade_id INT NOT NULL');
        $this->addSql('ALTER TABLE avatar_has_upgrades ADD CONSTRAINT FK_C30FFEC86383B10 FOREIGN KEY (avatar_id) REFERENCES avatar (id)');
        $this->addSql('ALTER TABLE avatar_has_upgrades ADD CONSTRAINT FK_C30FFEC98729BBE FOREIGN KEY (upgrade_id) REFERENCES avatar_upgrade (id)');
        $this->addSql('CREATE INDEX IDX_C30FFEC86383B10 ON avatar_has_upgrades (avatar_id)');
        $this->addSql('CREATE INDEX IDX_C30FFEC98729BBE ON avatar_has_upgrades (upgrade_id)');
        $this->addSql('ALTER TABLE classroom ADD teacher_id INT NOT NULL, ADD year_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE classroom ADD CONSTRAINT FK_497D309D41807E1D FOREIGN KEY (teacher_id) REFERENCES teacher (id)');
        $this->addSql('ALTER TABLE classroom ADD CONSTRAINT FK_497D309DD79FBF22 FOREIGN KEY (year_group_id) REFERENCES year_group (id)');
        $this->addSql('CREATE INDEX IDX_497D309D41807E1D ON classroom (teacher_id)');
        $this->addSql('CREATE INDEX IDX_497D309DD79FBF22 ON classroom (year_group_id)');
        $this->addSql('ALTER TABLE classroom_has_sessions ADD classroom_id INT NOT NULL, ADD session_id INT NOT NULL');
        $this->addSql('ALTER TABLE classroom_has_sessions ADD CONSTRAINT FK_C12CE3A76278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('ALTER TABLE classroom_has_sessions ADD CONSTRAINT FK_C12CE3A7613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_C12CE3A76278D5A8 ON classroom_has_sessions (classroom_id)');
        $this->addSql('CREATE INDEX IDX_C12CE3A7613FECDF ON classroom_has_sessions (session_id)');
        $this->addSql('ALTER TABLE session ADD year_group_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D4D79FBF22 FOREIGN KEY (year_group_id) REFERENCES year_group (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D4D79FBF22 ON session (year_group_id)');
        $this->addSql('ALTER TABLE student ADD classroom_id INT NOT NULL');
        $this->addSql('ALTER TABLE student ADD CONSTRAINT FK_B723AF336278D5A8 FOREIGN KEY (classroom_id) REFERENCES classroom (id)');
        $this->addSql('CREATE INDEX IDX_B723AF336278D5A8 ON student (classroom_id)');
        $this->addSql('ALTER TABLE student_completes_sessions ADD student_id INT NOT NULL, ADD session_id INT NOT NULL');
        $this->addSql('ALTER TABLE student_completes_sessions ADD CONSTRAINT FK_BABE194CB944F1A FOREIGN KEY (student_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE student_completes_sessions ADD CONSTRAINT FK_BABE194613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('CREATE INDEX IDX_BABE194CB944F1A ON student_completes_sessions (student_id)');
        $this->addSql('CREATE INDEX IDX_BABE194613FECDF ON student_completes_sessions (session_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE avatar_has_upgrades DROP FOREIGN KEY FK_C30FFEC86383B10');
        $this->addSql('ALTER TABLE avatar_has_upgrades DROP FOREIGN KEY FK_C30FFEC98729BBE');
        $this->addSql('DROP INDEX IDX_C30FFEC86383B10 ON avatar_has_upgrades');
        $this->addSql('DROP INDEX IDX_C30FFEC98729BBE ON avatar_has_upgrades');
        $this->addSql('ALTER TABLE avatar_has_upgrades DROP avatar_id, DROP upgrade_id');
        $this->addSql('ALTER TABLE student DROP FOREIGN KEY FK_B723AF336278D5A8');
        $this->addSql('DROP INDEX IDX_B723AF336278D5A8 ON student');
        $this->addSql('ALTER TABLE student DROP classroom_id');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D4D79FBF22');
        $this->addSql('DROP INDEX IDX_D044D5D4D79FBF22 ON session');
        $this->addSql('ALTER TABLE session DROP year_group_id');
        $this->addSql('ALTER TABLE student_completes_sessions DROP FOREIGN KEY FK_BABE194CB944F1A');
        $this->addSql('ALTER TABLE student_completes_sessions DROP FOREIGN KEY FK_BABE194613FECDF');
        $this->addSql('DROP INDEX IDX_BABE194CB944F1A ON student_completes_sessions');
        $this->addSql('DROP INDEX IDX_BABE194613FECDF ON student_completes_sessions');
        $this->addSql('ALTER TABLE student_completes_sessions DROP student_id, DROP session_id');
        $this->addSql('ALTER TABLE classroom_has_sessions DROP FOREIGN KEY FK_C12CE3A76278D5A8');
        $this->addSql('ALTER TABLE classroom_has_sessions DROP FOREIGN KEY FK_C12CE3A7613FECDF');
        $this->addSql('DROP INDEX IDX_C12CE3A76278D5A8 ON classroom_has_sessions');
        $this->addSql('DROP INDEX IDX_C12CE3A7613FECDF ON classroom_has_sessions');
        $this->addSql('ALTER TABLE classroom_has_sessions DROP classroom_id, DROP session_id');
        $this->addSql('ALTER TABLE classroom DROP FOREIGN KEY FK_497D309D41807E1D');
        $this->addSql('ALTER TABLE classroom DROP FOREIGN KEY FK_497D309DD79FBF22');
        $this->addSql('DROP INDEX IDX_497D309D41807E1D ON classroom');
        $this->addSql('DROP INDEX IDX_497D309DD79FBF22 ON classroom');
        $this->addSql('ALTER TABLE classroom DROP teacher_id, DROP year_group_id');
        $this->addSql('ALTER TABLE avatar DROP FOREIGN KEY FK_1677722FCB944F1A');
        $this->addSql('DROP INDEX UNIQ_1677722FCB944F1A ON avatar');
        $this->addSql('ALTER TABLE avatar DROP student_id');
    }
}
