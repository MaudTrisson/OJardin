<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230819163056 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC61D935652');
        $this->addSql('DROP INDEX IDX_AC0FEDC61D935652 ON flowerbed');
        $this->addSql('ALTER TABLE flowerbed DROP plant_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed ADD plant_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC61D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AC0FEDC61D935652 ON flowerbed (plant_id)');
    }
}
