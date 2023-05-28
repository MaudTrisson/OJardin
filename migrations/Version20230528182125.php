<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528182125 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed ADD ground_acidities_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC62C0447FC FOREIGN KEY (ground_acidities_id) REFERENCES ground_acidity (id)');
        $this->addSql('CREATE INDEX IDX_AC0FEDC62C0447FC ON flowerbed (ground_acidities_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC62C0447FC');
        $this->addSql('DROP INDEX IDX_AC0FEDC62C0447FC ON flowerbed');
        $this->addSql('ALTER TABLE flowerbed DROP ground_acidities_id');
    }
}
