<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528203851 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flowerbed_plant (id INT AUTO_INCREMENT NOT NULL, plant_id INT NOT NULL, flowerbed_id INT NOT NULL, planting_date DATETIME NOT NULL, INDEX IDX_BECFAB7B1D935652 (plant_id), INDEX IDX_BECFAB7B880CBF5E (flowerbed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flowerbed_plant ADD CONSTRAINT FK_BECFAB7B1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('ALTER TABLE flowerbed_plant ADD CONSTRAINT FK_BECFAB7B880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed_plant DROP FOREIGN KEY FK_BECFAB7B1D935652');
        $this->addSql('ALTER TABLE flowerbed_plant DROP FOREIGN KEY FK_BECFAB7B880CBF5E');
        $this->addSql('DROP TABLE flowerbed_plant');
    }
}
