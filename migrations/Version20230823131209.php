<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230823131209 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EAFCE83E5F');
        $this->addSql('CREATE TABLE advice_department (advice_id INT NOT NULL, department_id INT NOT NULL, INDEX IDX_10E8DF5112998205 (advice_id), INDEX IDX_10E8DF51AE80F5DF (department_id), PRIMARY KEY(advice_id, department_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advice_department ADD CONSTRAINT FK_10E8DF5112998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_department ADD CONSTRAINT FK_10E8DF51AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_region DROP FOREIGN KEY FK_5AF87F1112998205');
        $this->addSql('ALTER TABLE advice_region DROP FOREIGN KEY FK_5AF87F1198260155');
        $this->addSql('DROP TABLE advice_region');
        $this->addSql('DROP TABLE region');
        $this->addSql('DROP INDEX IDX_3C0918EAFCE83E5F ON garden');
        $this->addSql('ALTER TABLE garden CHANGE regions_id departments_id INT NOT NULL');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EAF1B3F295 FOREIGN KEY (departments_id) REFERENCES department (id)');
        $this->addSql('CREATE INDEX IDX_3C0918EAF1B3F295 ON garden (departments_id)');
        $this->addSql('ALTER TABLE plant ADD CONSTRAINT FK_AB030D7212469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_AB030D7212469DE2 ON plant (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE garden DROP FOREIGN KEY FK_3C0918EAF1B3F295');
        $this->addSql('CREATE TABLE advice_region (advice_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_5AF87F1112998205 (advice_id), INDEX IDX_5AF87F1198260155 (region_id), PRIMARY KEY(advice_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE region (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE advice_region ADD CONSTRAINT FK_5AF87F1112998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_region ADD CONSTRAINT FK_5AF87F1198260155 FOREIGN KEY (region_id) REFERENCES region (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_department DROP FOREIGN KEY FK_10E8DF5112998205');
        $this->addSql('ALTER TABLE advice_department DROP FOREIGN KEY FK_10E8DF51AE80F5DF');
        $this->addSql('DROP TABLE advice_department');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP INDEX IDX_3C0918EAF1B3F295 ON garden');
        $this->addSql('ALTER TABLE garden CHANGE departments_id regions_id INT NOT NULL');
        $this->addSql('ALTER TABLE garden ADD CONSTRAINT FK_3C0918EAFCE83E5F FOREIGN KEY (regions_id) REFERENCES region (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_3C0918EAFCE83E5F ON garden (regions_id)');
        $this->addSql('ALTER TABLE plant DROP FOREIGN KEY FK_AB030D7212469DE2');
        $this->addSql('DROP INDEX IDX_AB030D7212469DE2 ON plant');
    }
}
