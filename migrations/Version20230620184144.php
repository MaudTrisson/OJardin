<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230620184144 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC620B760D1');
        $this->addSql('DROP INDEX IDX_AC0FEDC620B760D1 ON flowerbed');
        $this->addSql('ALTER TABLE flowerbed ADD shadowtype TINYINT(1) NOT NULL, DROP shadow_type_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed ADD shadow_type_id INT DEFAULT NULL, DROP shadowtype');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC620B760D1 FOREIGN KEY (shadow_type_id) REFERENCES shadow_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_AC0FEDC620B760D1 ON flowerbed (shadow_type_id)');
    }
}
