<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528200935 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden_flowerbed DROP FOREIGN KEY FK_A6C7E50739F3B087');
        $this->addSql('ALTER TABLE garden_flowerbed DROP FOREIGN KEY FK_A6C7E507880CBF5E');
        $this->addSql('ALTER TABLE plant_maintenance_action DROP FOREIGN KEY FK_70A573C91D935652');
        $this->addSql('ALTER TABLE plant_maintenance_action DROP FOREIGN KEY FK_70A573C9D8F3D444');
        $this->addSql('ALTER TABLE store_weekday DROP FOREIGN KEY FK_5286F66848516439');
        $this->addSql('ALTER TABLE store_weekday DROP FOREIGN KEY FK_5286F668B092A811');
        $this->addSql('ALTER TABLE user_garden DROP FOREIGN KEY FK_595B03D739F3B087');
        $this->addSql('ALTER TABLE user_garden DROP FOREIGN KEY FK_595B03D7A76ED395');
        $this->addSql('DROP TABLE garden_flowerbed');
        $this->addSql('DROP TABLE plant_maintenance_action');
        $this->addSql('DROP TABLE store_weekday');
        $this->addSql('DROP TABLE user_garden');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE garden_flowerbed (garden_id INT NOT NULL, flowerbed_id INT NOT NULL, INDEX IDX_A6C7E50739F3B087 (garden_id), INDEX IDX_A6C7E507880CBF5E (flowerbed_id), PRIMARY KEY(garden_id, flowerbed_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE plant_maintenance_action (plant_id INT NOT NULL, maintenance_action_id INT NOT NULL, INDEX IDX_70A573C91D935652 (plant_id), INDEX IDX_70A573C9D8F3D444 (maintenance_action_id), PRIMARY KEY(plant_id, maintenance_action_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE store_weekday (store_id INT NOT NULL, weekday_id INT NOT NULL, INDEX IDX_5286F66848516439 (weekday_id), INDEX IDX_5286F668B092A811 (store_id), PRIMARY KEY(store_id, weekday_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_garden (user_id INT NOT NULL, garden_id INT NOT NULL, INDEX IDX_595B03D739F3B087 (garden_id), INDEX IDX_595B03D7A76ED395 (user_id), PRIMARY KEY(user_id, garden_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE garden_flowerbed ADD CONSTRAINT FK_A6C7E50739F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden_flowerbed ADD CONSTRAINT FK_A6C7E507880CBF5E FOREIGN KEY (flowerbed_id) REFERENCES flowerbed (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD CONSTRAINT FK_70A573C91D935652 FOREIGN KEY (plant_id) REFERENCES plant (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE plant_maintenance_action ADD CONSTRAINT FK_70A573C9D8F3D444 FOREIGN KEY (maintenance_action_id) REFERENCES maintenance_action (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_weekday ADD CONSTRAINT FK_5286F66848516439 FOREIGN KEY (weekday_id) REFERENCES week_day (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_weekday ADD CONSTRAINT FK_5286F668B092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_garden ADD CONSTRAINT FK_595B03D739F3B087 FOREIGN KEY (garden_id) REFERENCES garden (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_garden ADD CONSTRAINT FK_595B03D7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE CASCADE');
    }
}
