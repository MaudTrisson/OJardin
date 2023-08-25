<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230825103721 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flowerbed_plant_maintenance_action (id INT AUTO_INCREMENT NOT NULL, flowerbed_plant_id INT NOT NULL, maintenance_action_id INT NOT NULL, achievement_date DATE NOT NULL, INDEX IDX_BD1B29E578CDF83C (flowerbed_plant_id), INDEX IDX_BD1B29E5D8F3D444 (maintenance_action_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flowerbed_plant_maintenance_action ADD CONSTRAINT FK_BD1B29E578CDF83C FOREIGN KEY (flowerbed_plant_id) REFERENCES flowerbed_plant (id)');
        $this->addSql('ALTER TABLE flowerbed_plant_maintenance_action ADD CONSTRAINT FK_BD1B29E5D8F3D444 FOREIGN KEY (maintenance_action_id) REFERENCES maintenance_action (id)');
        $this->addSql('ALTER TABLE garden CHANGE departments_id department_id INT NOT NULL');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EAAE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_3C0918EAAE80F5DF ON garden (department_id)');
        $this->addSql('ALTER TABLE maintenance_action ADD image VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD frequency_days INT NOT NULL, DROP due_date, DROP achievement');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed_plant_maintenance_action DROP FOREIGN KEY FK_BD1B29E578CDF83C');
        $this->addSql('ALTER TABLE flowerbed_plant_maintenance_action DROP FOREIGN KEY FK_BD1B29E5D8F3D444');
        $this->addSql('DROP TABLE flowerbed_plant_maintenance_action');
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EAAE80F5DF');
        $this->addSql('DROP INDEX IDX_3C0918EAAE80F5DF ON garden');
        $this->addSql('ALTER TABLE garden CHANGE department_id departments_id INT NOT NULL');
        $this->addSql('ALTER TABLE maintenance_action DROP image');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD due_date DATETIME NOT NULL, ADD achievement DATETIME NOT NULL, DROP frequency_days');
    }
}
