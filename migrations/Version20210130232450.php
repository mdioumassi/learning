<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210130232450 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level DROP FOREIGN KEY FK_9AEACC13161BA2FF');
        $this->addSql('DROP INDEX IDX_9AEACC13161BA2FF ON level');
        $this->addSql('ALTER TABLE level DROP trainings_id');
        $this->addSql('ALTER TABLE training ADD level_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE training ADD CONSTRAINT FK_D5128A8F5FB14BA7 FOREIGN KEY (level_id) REFERENCES level (id)');
        $this->addSql('CREATE INDEX IDX_D5128A8F5FB14BA7 ON training (level_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE level ADD trainings_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE level ADD CONSTRAINT FK_9AEACC13161BA2FF FOREIGN KEY (trainings_id) REFERENCES training (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_9AEACC13161BA2FF ON level (trainings_id)');
        $this->addSql('ALTER TABLE training DROP FOREIGN KEY FK_D5128A8F5FB14BA7');
        $this->addSql('DROP INDEX IDX_D5128A8F5FB14BA7 ON training');
        $this->addSql('ALTER TABLE training DROP level_id');
    }
}
