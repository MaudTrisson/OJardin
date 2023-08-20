<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819154031 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed_plant DROP FOREIGN KEY FK_BECFAB7B880CBF5E');
        $this->addSql('DROP INDEX IDX_BECFAB7B880CBF5E ON flowerbed_plant');
        $this->addSql('ALTER TABLE flowerbed_plant DROP flowerbed_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed_plant ADD flowerbed_id INT NOT NULL');
        $this->addSql('ALTER TABLE flowerbed_plant ADD CONSTRAINT FK_BECFAB7B880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BECFAB7B880CBF5E ON flowerbed_plant (flowerbed_id)');
    }
}
