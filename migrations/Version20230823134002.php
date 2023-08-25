<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230823134002 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE advice_department DROP FOREIGN KEY FK_10E8DF5112998205');
        $this->addSql('ALTER TABLE advice_department DROP FOREIGN KEY FK_10E8DF51AE80F5DF');
        $this->addSql('DROP TABLE advice_department');
        $this->addSql('DROP TABLE department');
        $this->addSql('ALTER TABLE garden DROP departments_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE advice_department (advice_id INT NOT NULL, department_id INT NOT NULL, INDEX IDX_10E8DF5112998205 (advice_id), INDEX IDX_10E8DF51AE80F5DF (department_id), PRIMARY KEY(advice_id, department_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE advice_department ADD CONSTRAINT FK_10E8DF5112998205 FOREIGN KEY (advice_id) REFERENCES advice (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE advice_department ADD CONSTRAINT FK_10E8DF51AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE garden ADD departments_id INT NOT NULL');
    }
}
