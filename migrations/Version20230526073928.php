<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230526073928 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advice CHANGE garden_size garden_size NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE flowerbed CHANGE startpoint startpoint INT NOT NULL, CHANGE width width NUMERIC(10, 2) NOT NULL, CHANGE height height NUMERIC(10, 2) NOT NULL, CHANGE ray ray NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE ground_acidity CHANGE high_fork high_fork NUMERIC(4, 2) NOT NULL, CHANGE low_fork low_fork NUMERIC(4, 2) NOT NULL');
        $this->addSql('ALTER TABLE plant CHANGE height height NUMERIC(10, 2) NOT NULL, CHANGE width width NUMERIC(10, 2) NOT NULL, CHANGE rainfall_rate_need rainfall_rate_need NUMERIC(10, 2) NOT NULL, CHANGE sunshine_rate_need sunshine_rate_need NUMERIC(4, 2) NOT NULL, CHANGE freeze_sensibility_max freeze_sensibility_max NUMERIC(4, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE plot_point CHANGE x x INT NOT NULL, CHANGE y y INT NOT NULL, CHANGE level sequence INT NOT NULL');
        $this->addSql('ALTER TABLE store DROP open_hour');
        $this->addSql('ALTER TABLE user DROP role');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advice CHANGE garden_size garden_size DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE ground_acidity CHANGE high_fork high_fork DOUBLE PRECISION NOT NULL, CHANGE low_fork low_fork DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE plot_point CHANGE x x DOUBLE PRECISION NOT NULL, CHANGE y y DOUBLE PRECISION NOT NULL, CHANGE sequence level INT NOT NULL');
        $this->addSql('ALTER TABLE plant CHANGE height height DOUBLE PRECISION NOT NULL, CHANGE width width DOUBLE PRECISION NOT NULL, CHANGE rainfall_rate_need rainfall_rate_need DOUBLE PRECISION NOT NULL, CHANGE sunshine_rate_need sunshine_rate_need DOUBLE PRECISION NOT NULL, CHANGE freeze_sensibility_max freeze_sensibility_max DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE user ADD role INT NOT NULL');
        $this->addSql('ALTER TABLE flowerbed CHANGE startpoint startpoint DOUBLE PRECISION NOT NULL, CHANGE width width DOUBLE PRECISION NOT NULL, CHANGE height height DOUBLE PRECISION NOT NULL, CHANGE ray ray DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE store ADD open_hour NUMERIC(4, 2) DEFAULT NULL');
    }
}
