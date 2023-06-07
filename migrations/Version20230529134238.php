<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529134238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flowerbed_plant_desease (id INT AUTO_INCREMENT NOT NULL, flowerbedplant_id INT DEFAULT NULL, desease_id INT DEFAULT NULL, INDEX IDX_F76E5B8187AC1F77 (flowerbedplant_id), INDEX IDX_F76E5B813E01A055 (desease_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flowerbed_plant_desease ADD CONSTRAINT FK_F76E5B8187AC1F77 FOREIGN KEY (flowerbedplant_id) REFERENCES flowerbed_plant (id)');
        $this->addSql('ALTER TABLE flowerbed_plant_desease ADD CONSTRAINT FK_F76E5B813E01A055 FOREIGN KEY (desease_id) REFERENCES desease (id)');
        $this->addSql('ALTER TABLE plant_desease DROP FOREIGN KEY FK_27060C31D935652');
        $this->addSql('ALTER TABLE plant_desease DROP FOREIGN KEY FK_27060C33E01A055');
        $this->addSql('DROP TABLE plant_desease');
        $this->addSql('ALTER TABLE plant_store DROP FOREIGN KEY FK_C8DC1FF1D935652');
        $this->addSql('ALTER TABLE plant_store DROP FOREIGN KEY FK_C8DC1FFB092A811');
        $this->addSql('ALTER TABLE plant_store ADD id INT AUTO_INCREMENT NOT NULL, ADD price NUMERIC(10, 2) DEFAULT NULL, ADD qty_in_stock INT NOT NULL, CHANGE plant_id plant_id INT DEFAULT NULL, CHANGE store_id store_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE plant_store ADD CONSTRAINT FK_C8DC1FF1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('ALTER TABLE plant_store ADD CONSTRAINT FK_C8DC1FFB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE store DROP price, DROP qty_in_stock');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plant_desease (plant_id INT NOT NULL, desease_id INT NOT NULL, INDEX IDX_27060C31D935652 (plant_id), INDEX IDX_27060C33E01A055 (desease_id), PRIMARY KEY(plant_id, desease_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE plant_desease ADD CONSTRAINT FK_27060C31D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_desease ADD CONSTRAINT FK_27060C33E01A055 FOREIGN KEY (desease_id) REFERENCES desease (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flowerbed_plant_desease DROP FOREIGN KEY FK_F76E5B8187AC1F77');
        $this->addSql('ALTER TABLE flowerbed_plant_desease DROP FOREIGN KEY FK_F76E5B813E01A055');
        $this->addSql('DROP TABLE flowerbed_plant_desease');
        $this->addSql('ALTER TABLE plant_store MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE plant_store DROP FOREIGN KEY FK_C8DC1FF1D935652');
        $this->addSql('ALTER TABLE plant_store DROP FOREIGN KEY FK_C8DC1FFB092A811');
        $this->addSql('DROP INDEX `PRIMARY` ON plant_store');
        $this->addSql('ALTER TABLE plant_store DROP id, DROP price, DROP qty_in_stock, CHANGE plant_id plant_id INT NOT NULL, CHANGE store_id store_id INT NOT NULL');
        $this->addSql('ALTER TABLE plant_store ADD CONSTRAINT FK_C8DC1FF1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_store ADD CONSTRAINT FK_C8DC1FFB092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_store ADD PRIMARY KEY (plant_id, store_id)');
        $this->addSql('ALTER TABLE store ADD price NUMERIC(10, 2) NOT NULL, ADD qty_in_stock INT NOT NULL');
    }
}
