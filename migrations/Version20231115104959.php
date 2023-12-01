<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115104959 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_autre_tarif (id INT AUTO_INCREMENT NOT NULL, ct_usage_imprime_technique_id_id INT NOT NULL, aut_prix DOUBLE PRECISION NOT NULL, aut_arrete VARCHAR(255) NOT NULL, aut_date DATE DEFAULT NULL, INDEX IDX_3BA0A4FE32BBA609 (ct_usage_imprime_technique_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_historique_type (id INT AUTO_INCREMENT NOT NULL, hst_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_type_visite (id INT AUTO_INCREMENT NOT NULL, tpv_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_usage_imprime_technique (id INT AUTO_INCREMENT NOT NULL, uit_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_utilisation (id INT AUTO_INCREMENT NOT NULL, ut_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_visite_extra (id INT AUTO_INCREMENT NOT NULL, vste_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_zone_desserte (id INT AUTO_INCREMENT NOT NULL, zd_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_autre_tarif ADD CONSTRAINT FK_3BA0A4FE32BBA609 FOREIGN KEY (ct_usage_imprime_technique_id_id) REFERENCES ct_usage_imprime_technique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_autre_tarif DROP FOREIGN KEY FK_3BA0A4FE32BBA609');
        $this->addSql('DROP TABLE ct_autre_tarif');
        $this->addSql('DROP TABLE ct_historique_type');
        $this->addSql('DROP TABLE ct_type_visite');
        $this->addSql('DROP TABLE ct_usage_imprime_technique');
        $this->addSql('DROP TABLE ct_utilisation');
        $this->addSql('DROP TABLE ct_visite_extra');
        $this->addSql('DROP TABLE ct_zone_desserte');
    }
}
