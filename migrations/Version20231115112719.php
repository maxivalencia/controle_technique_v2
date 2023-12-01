<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115112719 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_centre (id INT AUTO_INCREMENT NOT NULL, ct_province_id_id INT NOT NULL, ctr_nom VARCHAR(255) NOT NULL, ctr_code VARCHAR(255) NOT NULL, ctr_created_at DATE NOT NULL, ctr_updated_at DATE DEFAULT NULL, INDEX IDX_902E42D3E4756BB (ct_province_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_centre ADD CONSTRAINT FK_902E42D3E4756BB FOREIGN KEY (ct_province_id_id) REFERENCES ct_province (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_centre DROP FOREIGN KEY FK_902E42D3E4756BB');
        $this->addSql('DROP TABLE ct_centre');
    }
}
