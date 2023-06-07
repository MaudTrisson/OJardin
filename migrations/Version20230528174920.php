<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528174920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE garden_flowerbed (garden_id INT NOT NULL, flowerbed_id INT NOT NULL, INDEX IDX_A6C7E50739F3B087 (garden_id), INDEX IDX_A6C7E507880CBF5E (flowerbed_id), PRIMARY KEY(garden_id, flowerbed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_garden (user_id INT NOT NULL, garden_id INT NOT NULL, INDEX IDX_595B03D7A76ED395 (user_id), INDEX IDX_595B03D739F3B087 (garden_id), PRIMARY KEY(user_id, garden_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE garden_flowerbed ADD CONSTRAINT FK_A6C7E50739F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden_flowerbed ADD CONSTRAINT FK_A6C7E507880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_garden ADD CONSTRAINT FK_595B03D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_garden ADD CONSTRAINT FK_595B03D739F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden ADD regions_id INT NOT NULL');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EAFCE83E5F FOREIGN KEY (regions_id) REFERENCES region (id)');
        $this->addSql('CREATE INDEX IDX_3C0918EAFCE83E5F ON garden (regions_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden_flowerbed DROP FOREIGN KEY FK_A6C7E50739F3B087');
        $this->addSql('ALTER TABLE garden_flowerbed DROP FOREIGN KEY FK_A6C7E507880CBF5E');
        $this->addSql('ALTER TABLE user_garden DROP FOREIGN KEY FK_595B03D7A76ED395');
        $this->addSql('ALTER TABLE user_garden DROP FOREIGN KEY FK_595B03D739F3B087');
        $this->addSql('DROP TABLE garden_flowerbed');
        $this->addSql('DROP TABLE user_garden');
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EAFCE83E5F');
        $this->addSql('DROP INDEX IDX_3C0918EAFCE83E5F ON garden');
        $this->addSql('ALTER TABLE garden DROP regions_id');
    }
}
