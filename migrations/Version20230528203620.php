<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528203620 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE garden_flowerbed (id INT AUTO_INCREMENT NOT NULL, garden_id INT NOT NULL, flowerbed_id INT NOT NULL, flowerbed_level INT NOT NULL, INDEX IDX_A6C7E50739F3B087 (garden_id), INDEX IDX_A6C7E507880CBF5E (flowerbed_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE garden_flowerbed ADD CONSTRAINT FK_A6C7E50739F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('ALTER TABLE garden_flowerbed ADD CONSTRAINT FK_A6C7E507880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden_flowerbed DROP FOREIGN KEY FK_A6C7E50739F3B087');
        $this->addSql('ALTER TABLE garden_flowerbed DROP FOREIGN KEY FK_A6C7E507880CBF5E');
        $this->addSql('DROP TABLE garden_flowerbed');
    }
}
