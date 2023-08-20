<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819163255 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed_plant ADD garden_id INT NOT NULL');
        $this->addSql('ALTER TABLE flowerbed_plant ADD CONSTRAINT FK_BECFAB7B39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_BECFAB7B39F3B087 ON flowerbed_plant (garden_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed_plant DROP FOREIGN KEY FK_BECFAB7B39F3B087');
        $this->addSql('DROP INDEX IDX_BECFAB7B39F3B087 ON flowerbed_plant');
        $this->addSql('ALTER TABLE flowerbed_plant DROP garden_id');
    }
}
