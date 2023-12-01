<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115080646 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_genre_tarif (id INT AUTO_INCREMENT NOT NULL, ct_genre_id_id INT NOT NULL, grt_prix DOUBLE PRECISION NOT NULL, grt_annee DATE NOT NULL, INDEX IDX_CD5527BABC603024 (ct_genre_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_marque (id INT AUTO_INCREMENT NOT NULL, mrq_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_motif (id INT AUTO_INCREMENT NOT NULL, mtf_libelle VARCHAR(255) NOT NULL, mtf_is_calculable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_province (id INT AUTO_INCREMENT NOT NULL, prv_nom VARCHAR(255) NOT NULL, prv_code VARCHAR(255) NOT NULL, prv_created_at DATE NOT NULL, prv_updated_at DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_role (id INT AUTO_INCREMENT NOT NULL, role_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_source_energie (id INT AUTO_INCREMENT NOT NULL, sre_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_type_droit_ptac (id INT AUTO_INCREMENT NOT NULL, tp_dp_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_type_reception (id INT AUTO_INCREMENT NOT NULL, tprcp_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_type_usage (id INT AUTO_INCREMENT NOT NULL, tpu_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_genre_tarif ADD CONSTRAINT FK_CD5527BABC603024 FOREIGN KEY (ct_genre_id_id) REFERENCES ct_genre (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_genre_tarif DROP FOREIGN KEY FK_CD5527BABC603024');
        $this->addSql('DROP TABLE ct_genre_tarif');
        $this->addSql('DROP TABLE ct_marque');
        $this->addSql('DROP TABLE ct_motif');
        $this->addSql('DROP TABLE ct_province');
        $this->addSql('DROP TABLE ct_role');
        $this->addSql('DROP TABLE ct_source_energie');
        $this->addSql('DROP TABLE ct_type_droit_ptac');
        $this->addSql('DROP TABLE ct_type_reception');
        $this->addSql('DROP TABLE ct_type_usage');
    }
}
