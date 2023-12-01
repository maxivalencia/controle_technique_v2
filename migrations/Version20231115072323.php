<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115072323 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_anomalie (id INT AUTO_INCREMENT NOT NULL, ct_anomalie_type_id_id INT NOT NULL, anml_libelle VARCHAR(255) NOT NULL, anml_code VARCHAR(255) NOT NULL, anml_niveau_danger INT DEFAULT NULL, INDEX IDX_E48094659A0FD0E5 (ct_anomalie_type_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_anomalie_type (id INT AUTO_INCREMENT NOT NULL, atp_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_anomalie ADD CONSTRAINT FK_E48094659A0FD0E5 FOREIGN KEY (ct_anomalie_type_id_id) REFERENCES ct_anomalie_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_anomalie DROP FOREIGN KEY FK_E48094659A0FD0E5');
        $this->addSql('DROP TABLE ct_anomalie');
        $this->addSql('DROP TABLE ct_anomalie_type');
    }
}
