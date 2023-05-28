<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528195143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plantation ADD plant_id INT NOT NULL');
        $this->addSql('ALTER TABLE plantation ADD CONSTRAINT FK_B789E5BA1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B789E5BA1D935652 ON plantation (plant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plantation DROP FOREIGN KEY FK_B789E5BA1D935652');
        $this->addSql('DROP INDEX UNIQ_B789E5BA1D935652 ON plantation');
        $this->addSql('ALTER TABLE plantation DROP plant_id');
    }
}
