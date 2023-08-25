<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230615164415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed ADD formtype VARCHAR(255) NOT NULL, ADD topy INT NOT NULL, ADD letfx INT NOT NULL, ADD fill VARCHAR(255) NOT NULL, ADD stroke VARCHAR(255) NOT NULL, ADD flipangle DOUBLE PRECISION NOT NULL, DROP startpoint, CHANGE width width INT NOT NULL, CHANGE height height INT NOT NULL');
        $this->addSql('ALTER TABLE store_week_day DROP close_hours, CHANGE open_hours open_hours VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE flowerbed ADD startpoint INT DEFAULT NULL, DROP formtype, DROP topy, DROP letfx, DROP fill, DROP stroke, DROP flipangle, CHANGE width width NUMERIC(10, 2) DEFAULT NULL, CHANGE height height NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE store_week_day ADD close_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', CHANGE open_hours open_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }
}
