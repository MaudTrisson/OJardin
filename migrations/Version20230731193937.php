<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230731193937 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant ADD shadowtype_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D72122D24DD FOREIGN KEY (shadowtype_id) REFERENCES shadow_type (id)');
        $this->addSql('CREATE INDEX IDX_AB030D72122D24DD ON plant (shadowtype_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D72122D24DD');
        $this->addSql('DROP INDEX IDX_AB030D72122D24DD ON plant');
        $this->addSql('ALTER TABLE plant DROP shadowtype_id');
    }
}
