<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528202111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE garden_member (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, garden_id INT NOT NULL, is_owner TINYINT(1) NOT NULL, INDEX IDX_766F413CA76ED395 (user_id), INDEX IDX_766F413C39F3B087 (garden_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE garden_member ADD CONSTRAINT FK_766F413CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE garden_member ADD CONSTRAINT FK_766F413C39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('ALTER TABLE plantation DROP FOREIGN KEY FK_B789E5BA1D935652');
        $this->addSql('ALTER TABLE plantation DROP FOREIGN KEY FK_B789E5BA880CBF5E');
        $this->addSql('DROP TABLE plantation');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE plantation (id INT AUTO_INCREMENT NOT NULL, flowerbed_id INT NOT NULL, plant_id INT NOT NULL, planting_date DATETIME NOT NULL, INDEX IDX_B789E5BA880CBF5E (flowerbed_id), UNIQUE INDEX UNIQ_B789E5BA1D935652 (plant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE plantation ADD CONSTRAINT FK_B789E5BA1D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE plantation ADD CONSTRAINT FK_B789E5BA880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE garden_member DROP FOREIGN KEY FK_766F413CA76ED395');
        $this->addSql('ALTER TABLE garden_member DROP FOREIGN KEY FK_766F413C39F3B087');
        $this->addSql('DROP TABLE garden_member');
    }
}
