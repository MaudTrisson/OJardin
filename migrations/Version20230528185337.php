<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528185337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advice_usefulness (advice_id INT NOT NULL, usefulness_id INT NOT NULL, INDEX IDX_C88AB51712998205 (advice_id), INDEX IDX_C88AB517E639872C (usefulness_id), PRIMARY KEY(advice_id, usefulness_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advice_ground_acidity (advice_id INT NOT NULL, ground_acidity_id INT NOT NULL, INDEX IDX_9D551AB312998205 (advice_id), INDEX IDX_9D551AB3C92B554F (ground_acidity_id), PRIMARY KEY(advice_id, ground_acidity_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advice_ground_type (advice_id INT NOT NULL, ground_type_id INT NOT NULL, INDEX IDX_C957E2CA12998205 (advice_id), INDEX IDX_C957E2CA2D37CEC5 (ground_type_id), PRIMARY KEY(advice_id, ground_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advice_shadow_type (advice_id INT NOT NULL, shadow_type_id INT NOT NULL, INDEX IDX_BC12D50412998205 (advice_id), INDEX IDX_BC12D50420B760D1 (shadow_type_id), PRIMARY KEY(advice_id, shadow_type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE advice_region (advice_id INT NOT NULL, region_id INT NOT NULL, INDEX IDX_5AF87F1112998205 (advice_id), INDEX IDX_5AF87F1198260155 (region_id), PRIMARY KEY(advice_id, region_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category_advice (category_id INT NOT NULL, advice_id INT NOT NULL, INDEX IDX_6B6AC4F612469DE2 (category_id), INDEX IDX_6B6AC4F612998205 (advice_id), PRIMARY KEY(category_id, advice_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE desease_symptom (desease_id INT NOT NULL, symptom_id INT NOT NULL, INDEX IDX_CF58B44D3E01A055 (desease_id), INDEX IDX_CF58B44DDEEFDA95 (symptom_id), PRIMARY KEY(desease_id, symptom_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE store_weekday (store_id INT NOT NULL, weekday_id INT NOT NULL, INDEX IDX_5286F668B092A811 (store_id), INDEX IDX_5286F66848516439 (weekday_id), PRIMARY KEY(store_id, weekday_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE advice_usefulness ADD CONSTRAINT FK_C88AB51712998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_usefulness ADD CONSTRAINT FK_C88AB517E639872C FOREIGN KEY (usefulness_id) REFERENCES usefulness (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_ground_acidity ADD CONSTRAINT FK_9D551AB312998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_ground_acidity ADD CONSTRAINT FK_9D551AB3C92B554F FOREIGN KEY (ground_acidity_id) REFERENCES ground_acidity (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_ground_type ADD CONSTRAINT FK_C957E2CA12998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_ground_type ADD CONSTRAINT FK_C957E2CA2D37CEC5 FOREIGN KEY (ground_type_id) REFERENCES ground_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_shadow_type ADD CONSTRAINT FK_BC12D50412998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_shadow_type ADD CONSTRAINT FK_BC12D50420B760D1 FOREIGN KEY (shadow_type_id) REFERENCES shadow_type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_region ADD CONSTRAINT FK_5AF87F1112998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_region ADD CONSTRAINT FK_5AF87F1198260155 FOREIGN KEY (region_id) REFERENCES region (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_advice ADD CONSTRAINT FK_6B6AC4F612469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category_advice ADD CONSTRAINT FK_6B6AC4F612998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE desease_symptom ADD CONSTRAINT FK_CF58B44D3E01A055 FOREIGN KEY (desease_id) REFERENCES desease (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE desease_symptom ADD CONSTRAINT FK_CF58B44DDEEFDA95 FOREIGN KEY (symptom_id) REFERENCES symptom (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_weekday ADD CONSTRAINT FK_5286F668B092A811 FOREIGN KEY (store_id) REFERENCES store (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE store_weekday ADD CONSTRAINT FK_5286F66848516439 FOREIGN KEY (weekday_id) REFERENCES week_day (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advice_usefulness DROP FOREIGN KEY FK_C88AB51712998205');
        $this->addSql('ALTER TABLE advice_usefulness DROP FOREIGN KEY FK_C88AB517E639872C');
        $this->addSql('ALTER TABLE advice_ground_acidity DROP FOREIGN KEY FK_9D551AB312998205');
        $this->addSql('ALTER TABLE advice_ground_acidity DROP FOREIGN KEY FK_9D551AB3C92B554F');
        $this->addSql('ALTER TABLE advice_ground_type DROP FOREIGN KEY FK_C957E2CA12998205');
        $this->addSql('ALTER TABLE advice_ground_type DROP FOREIGN KEY FK_C957E2CA2D37CEC5');
        $this->addSql('ALTER TABLE advice_shadow_type DROP FOREIGN KEY FK_BC12D50412998205');
        $this->addSql('ALTER TABLE advice_shadow_type DROP FOREIGN KEY FK_BC12D50420B760D1');
        $this->addSql('ALTER TABLE advice_region DROP FOREIGN KEY FK_5AF87F1112998205');
        $this->addSql('ALTER TABLE advice_region DROP FOREIGN KEY FK_5AF87F1198260155');
        $this->addSql('ALTER TABLE category_advice DROP FOREIGN KEY FK_6B6AC4F612469DE2');
        $this->addSql('ALTER TABLE category_advice DROP FOREIGN KEY FK_6B6AC4F612998205');
        $this->addSql('ALTER TABLE desease_symptom DROP FOREIGN KEY FK_CF58B44D3E01A055');
        $this->addSql('ALTER TABLE desease_symptom DROP FOREIGN KEY FK_CF58B44DDEEFDA95');
        $this->addSql('ALTER TABLE store_weekday DROP FOREIGN KEY FK_5286F668B092A811');
        $this->addSql('ALTER TABLE store_weekday DROP FOREIGN KEY FK_5286F66848516439');
        $this->addSql('DROP TABLE advice_usefulness');
        $this->addSql('DROP TABLE advice_ground_acidity');
        $this->addSql('DROP TABLE advice_ground_type');
        $this->addSql('DROP TABLE advice_shadow_type');
        $this->addSql('DROP TABLE advice_region');
        $this->addSql('DROP TABLE category_advice');
        $this->addSql('DROP TABLE desease_symptom');
        $this->addSql('DROP TABLE store_weekday');
    }
}
