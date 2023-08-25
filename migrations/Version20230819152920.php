<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819152920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC68C9E07DF');
        $this->addSql('DROP INDEX UNIQ_AC0FEDC68C9E07DF ON flowerbed');
        $this->addSql('ALTER TABLE flowerbed CHANGE plant_id_id plant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC61D935652 FOREIGN KEY (plant_id) REFERENCES flowerbed_plant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC0FEDC61D935652 ON flowerbed (plant_id)');
        $this->addSql('ALTER TABLE flowerbed_plant DROP FOREIGN KEY FK_BECFAB7B880CBF5E');
        $this->addSql('DROP INDEX IDX_BECFAB7B880CBF5E ON flowerbed_plant');
        $this->addSql('ALTER TABLE flowerbed_plant DROP flowerbed_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC61D935652');
        $this->addSql('DROP INDEX UNIQ_AC0FEDC61D935652 ON flowerbed');
        $this->addSql('ALTER TABLE flowerbed CHANGE plant_id plant_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC68C9E07DF FOREIGN KEY (plant_id_id) REFERENCES flowerbed_plant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AC0FEDC68C9E07DF ON flowerbed (plant_id_id)');
        $this->addSql('ALTER TABLE flowerbed_plant ADD flowerbed_id INT NOT NULL');
        $this->addSql('ALTER TABLE flowerbed_plant ADD CONSTRAINT FK_BECFAB7B880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_BECFAB7B880CBF5E ON flowerbed_plant (flowerbed_id)');
    }
}
