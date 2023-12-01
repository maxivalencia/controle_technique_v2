<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118085510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_imprime_tech (id INT AUTO_INCREMENT NOT NULL, ct_user_id_id INT DEFAULT NULL, nom_imprime_tech VARCHAR(255) NOT NULL, ute_imprime_tech VARCHAR(255) NOT NULL, abrev_imprime_tech VARCHAR(255) NOT NULL, prtt_created_at DATE NOT NULL, prtt_updated_at DATE DEFAULT NULL, INDEX IDX_3F49AE42E4B6E02 (ct_user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_motif_tarif (id INT AUTO_INCREMENT NOT NULL, ct_motif_id_id INT DEFAULT NULL, ct_arrete_prix_id INT DEFAULT NULL, mtf_trf_prix DOUBLE PRECISION NOT NULL, mtf_trf_date DATE NOT NULL, INDEX IDX_110F10F8603A0766 (ct_motif_id_id), INDEX IDX_110F10F876255A68 (ct_arrete_prix_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_proces_verbal (id INT AUTO_INCREMENT NOT NULL, ct_arrete_prix_id_id INT DEFAULT NULL, pv_type VARCHAR(255) NOT NULL, pv_tarif DOUBLE PRECISION NOT NULL, INDEX IDX_556CD10D2BD53E47 (ct_arrete_prix_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_usage (id INT AUTO_INCREMENT NOT NULL, ct_type_usage_id_id INT DEFAULT NULL, usg_libelle VARCHAR(255) NOT NULL, usg_validite INT NOT NULL, usg_created DATE DEFAULT NULL, INDEX IDX_C8709F46EAF3885 (ct_type_usage_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_usage_tarif (id INT AUTO_INCREMENT NOT NULL, ct_usage_id_id INT DEFAULT NULL, ct_type_visite_id_id INT DEFAULT NULL, ct_arrete_prix_id_id INT DEFAULT NULL, usg_trf_annee VARCHAR(255) NOT NULL, usg_trf_prix DOUBLE PRECISION NOT NULL, INDEX IDX_FA9D5B818BA84C1F (ct_usage_id_id), INDEX IDX_FA9D5B81F18C25C4 (ct_type_visite_id_id), INDEX IDX_FA9D5B812BD53E47 (ct_arrete_prix_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_visite_extra_tarif (id INT AUTO_INCREMENT NOT NULL, ct_imprime_tech_id_id INT DEFAULT NULL, ct_arrete_prix_id_id INT DEFAULT NULL, vet_annee VARCHAR(255) NOT NULL, vet_prix DOUBLE PRECISION NOT NULL, INDEX IDX_E3F1985EB1D04D41 (ct_imprime_tech_id_id), INDEX IDX_E3F1985E2BD53E47 (ct_arrete_prix_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_imprime_tech ADD CONSTRAINT FK_3F49AE42E4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_motif_tarif ADD CONSTRAINT FK_110F10F8603A0766 FOREIGN KEY (ct_motif_id_id) REFERENCES ct_motif (id)');
        $this->addSql('ALTER TABLE ct_motif_tarif ADD CONSTRAINT FK_110F10F876255A68 FOREIGN KEY (ct_arrete_prix_id) REFERENCES ct_arrete_prix (id)');
        $this->addSql('ALTER TABLE ct_proces_verbal ADD CONSTRAINT FK_556CD10D2BD53E47 FOREIGN KEY (ct_arrete_prix_id_id) REFERENCES ct_arrete_prix (id)');
        $this->addSql('ALTER TABLE ct_usage ADD CONSTRAINT FK_C8709F46EAF3885 FOREIGN KEY (ct_type_usage_id_id) REFERENCES ct_type_usage (id)');
        $this->addSql('ALTER TABLE ct_usage_tarif ADD CONSTRAINT FK_FA9D5B818BA84C1F FOREIGN KEY (ct_usage_id_id) REFERENCES ct_usage (id)');
        $this->addSql('ALTER TABLE ct_usage_tarif ADD CONSTRAINT FK_FA9D5B81F18C25C4 FOREIGN KEY (ct_type_visite_id_id) REFERENCES ct_type_visite (id)');
        $this->addSql('ALTER TABLE ct_usage_tarif ADD CONSTRAINT FK_FA9D5B812BD53E47 FOREIGN KEY (ct_arrete_prix_id_id) REFERENCES ct_arrete_prix (id)');
        $this->addSql('ALTER TABLE ct_visite_extra_tarif ADD CONSTRAINT FK_E3F1985EB1D04D41 FOREIGN KEY (ct_imprime_tech_id_id) REFERENCES ct_imprime_tech (id)');
        $this->addSql('ALTER TABLE ct_visite_extra_tarif ADD CONSTRAINT FK_E3F1985E2BD53E47 FOREIGN KEY (ct_arrete_prix_id_id) REFERENCES ct_arrete_prix (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_imprime_tech DROP FOREIGN KEY FK_3F49AE42E4B6E02');
        $this->addSql('ALTER TABLE ct_motif_tarif DROP FOREIGN KEY FK_110F10F8603A0766');
        $this->addSql('ALTER TABLE ct_motif_tarif DROP FOREIGN KEY FK_110F10F876255A68');
        $this->addSql('ALTER TABLE ct_proces_verbal DROP FOREIGN KEY FK_556CD10D2BD53E47');
        $this->addSql('ALTER TABLE ct_usage DROP FOREIGN KEY FK_C8709F46EAF3885');
        $this->addSql('ALTER TABLE ct_usage_tarif DROP FOREIGN KEY FK_FA9D5B818BA84C1F');
        $this->addSql('ALTER TABLE ct_usage_tarif DROP FOREIGN KEY FK_FA9D5B81F18C25C4');
        $this->addSql('ALTER TABLE ct_usage_tarif DROP FOREIGN KEY FK_FA9D5B812BD53E47');
        $this->addSql('ALTER TABLE ct_visite_extra_tarif DROP FOREIGN KEY FK_E3F1985EB1D04D41');
        $this->addSql('ALTER TABLE ct_visite_extra_tarif DROP FOREIGN KEY FK_E3F1985E2BD53E47');
        $this->addSql('DROP TABLE ct_imprime_tech');
        $this->addSql('DROP TABLE ct_motif_tarif');
        $this->addSql('DROP TABLE ct_proces_verbal');
        $this->addSql('DROP TABLE ct_usage');
        $this->addSql('DROP TABLE ct_usage_tarif');
        $this->addSql('DROP TABLE ct_visite_extra_tarif');
    }
}
