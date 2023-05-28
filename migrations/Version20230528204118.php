<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528204118 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plant_maintenance_action (id INT AUTO_INCREMENT NOT NULL, plant_id INT NOT NULL, maintenance_action_id INT NOT NULL, due_date DATETIME NOT NULL, achievement DATETIME NOT NULL, INDEX IDX_70A573C91D935652 (plant_id), INDEX IDX_70A573C9D8F3D444 (maintenance_action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD CONSTRAINT FK_70A573C91D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD CONSTRAINT FK_70A573C9D8F3D444 FOREIGN KEY (maintenance_action_id) REFERENCES maintenance_action (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant_maintenance_action DROP FOREIGN KEY FK_70A573C91D935652');
        $this->addSql('ALTER TABLE plant_maintenance_action DROP FOREIGN KEY FK_70A573C9D8F3D444');
        $this->addSql('DROP TABLE plant_maintenance_action');
    }
}
