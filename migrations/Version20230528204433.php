<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230528204433 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE store_week_day (id INT AUTO_INCREMENT NOT NULL, store_id INT NOT NULL, week_day_id INT NOT NULL, open_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', close_hours LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_1B1A6C9BB092A811 (store_id), INDEX IDX_1B1A6C9B7DB83875 (week_day_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE store_week_day ADD CONSTRAINT FK_1B1A6C9BB092A811 FOREIGN KEY (store_id) REFERENCES store (id)');
        $this->addSql('ALTER TABLE store_week_day ADD CONSTRAINT FK_1B1A6C9B7DB83875 FOREIGN KEY (week_day_id) REFERENCES week_day (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE store_week_day DROP FOREIGN KEY FK_1B1A6C9BB092A811');
        $this->addSql('ALTER TABLE store_week_day DROP FOREIGN KEY FK_1B1A6C9B7DB83875');
        $this->addSql('DROP TABLE store_week_day');
    }
}
