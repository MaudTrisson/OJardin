<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528203050 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE garden_user (id INT AUTO_INCREMENT NOT NULL, is_owner TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE garden_member DROP FOREIGN KEY FK_766F413C39F3B087');
        $this->addSql('ALTER TABLE garden_member DROP FOREIGN KEY FK_766F413CA76ED395');
        $this->addSql('DROP TABLE garden_member');
        $this->addSql('ALTER TABLE user ADD garden_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D6D9DDA7 FOREIGN KEY (garden_user_id) REFERENCES garden_user (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D6D9DDA7 ON user (garden_user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D6D9DDA7');
        $this->addSql('CREATE TABLE garden_member (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, garden_id INT NOT NULL, is_owner TINYINT(1) NOT NULL, INDEX IDX_766F413C39F3B087 (garden_id), INDEX IDX_766F413CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE garden_member ADD CONSTRAINT FK_766F413C39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE garden_member ADD CONSTRAINT FK_766F413CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('DROP TABLE garden_user');
        $this->addSql('DROP INDEX IDX_8D93D649D6D9DDA7 ON user');
        $this->addSql('ALTER TABLE user DROP garden_user_id');
    }
}
