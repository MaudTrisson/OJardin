<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819152607 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed ADD plant_id_id INT DEFAULT NULL, DROP plant_id');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC68C9E07DF FOREIGN KEY (plant_id_id) REFERENCES flowerbed_plant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC0FEDC68C9E07DF ON flowerbed (plant_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC68C9E07DF');
        $this->addSql('DROP INDEX UNIQ_AC0FEDC68C9E07DF ON flowerbed');
        $this->addSql('ALTER TABLE flowerbed ADD plant_id INT NOT NULL, DROP plant_id_id');
    }
}
