<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528184539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plant_maintenance_action (plant_id INT NOT NULL, maintenance_action_id INT NOT NULL, INDEX IDX_70A573C91D935652 (plant_id), INDEX IDX_70A573C9D8F3D444 (maintenance_action_id), PRIMARY KEY(plant_id, maintenance_action_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant_desease (plant_id INT NOT NULL, desease_id INT NOT NULL, INDEX IDX_27060C31D935652 (plant_id), INDEX IDX_27060C33E01A055 (desease_id), PRIMARY KEY(plant_id, desease_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant_store (plant_id INT NOT NULL, store_id INT NOT NULL, INDEX IDX_C8DC1FF1D935652 (plant_id), INDEX IDX_C8DC1FFB092A811 (store_id), PRIMARY KEY(plant_id, store_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant_category (plant_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_2FEF59931D935652 (plant_id), INDEX IDX_2FEF599312469DE2 (category_id), PRIMARY KEY(plant_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant_usefulness (plant_id INT NOT NULL, usefulness_id INT NOT NULL, INDEX IDX_EB2EDC291D935652 (plant_id), INDEX IDX_EB2EDC29E639872C (usefulness_id), PRIMARY KEY(plant_id, usefulness_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant_ground_acidity (plant_id INT NOT NULL, ground_acidity_id INT NOT NULL, INDEX IDX_A2AB6D111D935652 (plant_id), INDEX IDX_A2AB6D11C92B554F (ground_acidity_id), PRIMARY KEY(plant_id, ground_acidity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plant_ground_type (plant_id INT NOT NULL, ground_type_id INT NOT NULL, INDEX IDX_8155B081D935652 (plant_id), INDEX IDX_8155B082D37CEC5 (ground_type_id), PRIMARY KEY(plant_id, ground_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD CONSTRAINT FK_70A573C91D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD CONSTRAINT FK_70A573C9D8F3D444 FOREIGN KEY (maintenance_action_id) REFERENCES maintenance_action (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_desease ADD CONSTRAINT FK_27060C31D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_desease ADD CONSTRAINT FK_27060C33E01A055 FOREIGN KEY (desease_id) REFERENCES desease (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_store ADD CONSTRAINT FK_C8DC1FF1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_store ADD CONSTRAINT FK_C8DC1FFB092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_category ADD CONSTRAINT FK_2FEF59931D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_category ADD CONSTRAINT FK_2FEF599312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_usefulness ADD CONSTRAINT FK_EB2EDC291D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_usefulness ADD CONSTRAINT FK_EB2EDC29E639872C FOREIGN KEY (usefulness_id) REFERENCES usefulness (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_ground_acidity ADD CONSTRAINT FK_A2AB6D111D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_ground_acidity ADD CONSTRAINT FK_A2AB6D11C92B554F FOREIGN KEY (ground_acidity_id) REFERENCES ground_acidity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_ground_type ADD CONSTRAINT FK_8155B081D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_ground_type ADD CONSTRAINT FK_8155B082D37CEC5 FOREIGN KEY (ground_type_id) REFERENCES ground_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant ADD color_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D727ADA1FB5 FOREIGN KEY (color_id) REFERENCES color (id)');
        $this->addSql('CREATE INDEX IDX_AB030D727ADA1FB5 ON plant (color_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant_maintenance_action DROP FOREIGN KEY FK_70A573C91D935652');
        $this->addSql('ALTER TABLE plant_maintenance_action DROP FOREIGN KEY FK_70A573C9D8F3D444');
        $this->addSql('ALTER TABLE plant_desease DROP FOREIGN KEY FK_27060C31D935652');
        $this->addSql('ALTER TABLE plant_desease DROP FOREIGN KEY FK_27060C33E01A055');
        $this->addSql('ALTER TABLE plant_store DROP FOREIGN KEY FK_C8DC1FF1D935652');
        $this->addSql('ALTER TABLE plant_store DROP FOREIGN KEY FK_C8DC1FFB092A811');
        $this->addSql('ALTER TABLE plant_category DROP FOREIGN KEY FK_2FEF59931D935652');
        $this->addSql('ALTER TABLE plant_category DROP FOREIGN KEY FK_2FEF599312469DE2');
        $this->addSql('ALTER TABLE plant_usefulness DROP FOREIGN KEY FK_EB2EDC291D935652');
        $this->addSql('ALTER TABLE plant_usefulness DROP FOREIGN KEY FK_EB2EDC29E639872C');
        $this->addSql('ALTER TABLE plant_ground_acidity DROP FOREIGN KEY FK_A2AB6D111D935652');
        $this->addSql('ALTER TABLE plant_ground_acidity DROP FOREIGN KEY FK_A2AB6D11C92B554F');
        $this->addSql('ALTER TABLE plant_ground_type DROP FOREIGN KEY FK_8155B081D935652');
        $this->addSql('ALTER TABLE plant_ground_type DROP FOREIGN KEY FK_8155B082D37CEC5');
        $this->addSql('DROP TABLE plant_maintenance_action');
        $this->addSql('DROP TABLE plant_desease');
        $this->addSql('DROP TABLE plant_store');
        $this->addSql('DROP TABLE plant_category');
        $this->addSql('DROP TABLE plant_usefulness');
        $this->addSql('DROP TABLE plant_ground_acidity');
        $this->addSql('DROP TABLE plant_ground_type');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D727ADA1FB5');
        $this->addSql('DROP INDEX IDX_AB030D727ADA1FB5 ON plant');
        $this->addSql('ALTER TABLE plant DROP color_id');
    }
}
