<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529141158 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden_advice DROP FOREIGN KEY FK_6209B5C912998205');
        $this->addSql('ALTER TABLE garden_advice DROP FOREIGN KEY FK_6209B5C939F3B087');
        $this->addSql('ALTER TABLE garden_advice ADD id INT AUTO_INCREMENT NOT NULL, CHANGE garden_id garden_id INT DEFAULT NULL, CHANGE advice_id advice_id INT DEFAULT NULL, DROP PRIMARY KEY, ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE garden_advice ADD CONSTRAINT FK_6209B5C912998205 FOREIGN KEY (advice_id) REFERENCES advice (id)');
        $this->addSql('ALTER TABLE garden_advice ADD CONSTRAINT FK_6209B5C939F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden_advice MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE garden_advice DROP FOREIGN KEY FK_6209B5C939F3B087');
        $this->addSql('ALTER TABLE garden_advice DROP FOREIGN KEY FK_6209B5C912998205');
        $this->addSql('DROP INDEX `PRIMARY` ON garden_advice');
        $this->addSql('ALTER TABLE garden_advice DROP id, CHANGE garden_id garden_id INT NOT NULL, CHANGE advice_id advice_id INT NOT NULL');
        $this->addSql('ALTER TABLE garden_advice ADD CONSTRAINT FK_6209B5C939F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden_advice ADD CONSTRAINT FK_6209B5C912998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden_advice ADD PRIMARY KEY (garden_id, advice_id)');
    }
}
