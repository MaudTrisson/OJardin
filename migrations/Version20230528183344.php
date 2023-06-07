<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528183344 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed ADD ground_type_id INT DEFAULT NULL, ADD shadow_type_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC62D37CEC5 FOREIGN KEY (ground_type_id) REFERENCES ground_type (id)');
        $this->addSql('ALTER TABLE flowerbed ADD CONSTRAINT FK_AC0FEDC620B760D1 FOREIGN KEY (shadow_type_id) REFERENCES shadow_type (id)');
        $this->addSql('CREATE INDEX IDX_AC0FEDC62D37CEC5 ON flowerbed (ground_type_id)');
        $this->addSql('CREATE INDEX IDX_AC0FEDC620B760D1 ON flowerbed (shadow_type_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC62D37CEC5');
        $this->addSql('ALTER TABLE flowerbed DROP FOREIGN KEY FK_AC0FEDC620B760D1');
        $this->addSql('DROP INDEX IDX_AC0FEDC62D37CEC5 ON flowerbed');
        $this->addSql('DROP INDEX IDX_AC0FEDC620B760D1 ON flowerbed');
        $this->addSql('ALTER TABLE flowerbed DROP ground_type_id, DROP shadow_type_id');
    }
}
