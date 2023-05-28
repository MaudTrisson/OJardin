<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528181653 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE flowerbed_plant (flowerbed_id INT NOT NULL, plant_id INT NOT NULL, INDEX IDX_BECFAB7B880CBF5E (flowerbed_id), INDEX IDX_BECFAB7B1D935652 (plant_id), PRIMARY KEY(flowerbed_id, plant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE garden_advice (garden_id INT NOT NULL, advice_id INT NOT NULL, INDEX IDX_6209B5C939F3B087 (garden_id), INDEX IDX_6209B5C912998205 (advice_id), PRIMARY KEY(garden_id, advice_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE flowerbed_plant ADD CONSTRAINT FK_BECFAB7B880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flowerbed_plant ADD CONSTRAINT FK_BECFAB7B1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden_advice ADD CONSTRAINT FK_6209B5C939F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden_advice ADD CONSTRAINT FK_6209B5C912998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE flowerbed CHANGE width width NUMERIC(10, 2) DEFAULT NULL, CHANGE height height NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE plot_point ADD flowerbed_id INT NOT NULL');
        $this->addSql('ALTER TABLE plot_point ADD CONSTRAINT FK_3227676A880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id)');
        $this->addSql('CREATE INDEX IDX_3227676A880CBF5E ON plot_point (flowerbed_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed_plant DROP FOREIGN KEY FK_BECFAB7B880CBF5E');
        $this->addSql('ALTER TABLE flowerbed_plant DROP FOREIGN KEY FK_BECFAB7B1D935652');
        $this->addSql('ALTER TABLE garden_advice DROP FOREIGN KEY FK_6209B5C939F3B087');
        $this->addSql('ALTER TABLE garden_advice DROP FOREIGN KEY FK_6209B5C912998205');
        $this->addSql('DROP TABLE flowerbed_plant');
        $this->addSql('DROP TABLE garden_advice');
        $this->addSql('ALTER TABLE plot_point DROP FOREIGN KEY FK_3227676A880CBF5E');
        $this->addSql('DROP INDEX IDX_3227676A880CBF5E ON plot_point');
        $this->addSql('ALTER TABLE plot_point DROP flowerbed_id');
        $this->addSql('ALTER TABLE flowerbed CHANGE width width NUMERIC(10, 2) NOT NULL, CHANGE height height NUMERIC(10, 2) NOT NULL');
    }
}
