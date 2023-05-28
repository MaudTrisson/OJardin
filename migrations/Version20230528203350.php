<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528203350 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden_user ADD user_id INT NOT NULL, ADD garden_id INT NOT NULL');
        $this->addSql('ALTER TABLE garden_user ADD CONSTRAINT FK_5B5D442CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE garden_user ADD CONSTRAINT FK_5B5D442C39F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id)');
        $this->addSql('CREATE INDEX IDX_5B5D442CA76ED395 ON garden_user (user_id)');
        $this->addSql('CREATE INDEX IDX_5B5D442C39F3B087 ON garden_user (garden_id)');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D6D9DDA7');
        $this->addSql('DROP INDEX IDX_8D93D649D6D9DDA7 ON user');
        $this->addSql('ALTER TABLE user DROP garden_user_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden_user DROP FOREIGN KEY FK_5B5D442CA76ED395');
        $this->addSql('ALTER TABLE garden_user DROP FOREIGN KEY FK_5B5D442C39F3B087');
        $this->addSql('DROP INDEX IDX_5B5D442CA76ED395 ON garden_user');
        $this->addSql('DROP INDEX IDX_5B5D442C39F3B087 ON garden_user');
        $this->addSql('ALTER TABLE garden_user DROP user_id, DROP garden_id');
        $this->addSql('ALTER TABLE user ADD garden_user_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D6D9DDA7 FOREIGN KEY (garden_user_id) REFERENCES garden_user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_8D93D649D6D9DDA7 ON user (garden_user_id)');
    }
}
