<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230821100310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant_category DROP FOREIGN KEY FK_2FEF599312469DE2');
        $this->addSql('ALTER TABLE plant_category DROP FOREIGN KEY FK_2FEF59931D935652');
        $this->addSql('DROP TABLE plant_category');
        $this->addSql('ALTER TABLE plant ADD category_id INT NOT NULL');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_AB030D7212469DE2 ON plant (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plant_category (plant_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_2FEF59931D935652 (plant_id), INDEX IDX_2FEF599312469DE2 (category_id), PRIMARY KEY(plant_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE plant_category ADD CONSTRAINT FK_2FEF599312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_category ADD CONSTRAINT FK_2FEF59931D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7212469DE2');
        $this->addSql('DROP INDEX IDX_AB030D7212469DE2 ON plant');
        $this->addSql('ALTER TABLE plant DROP category_id');
    }
}
