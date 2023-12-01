<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118095827 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_bordereau (id INT AUTO_INCREMENT NOT NULL, ct_centre_id_id INT DEFAULT NULL, ct_imprime_tech_id_id INT DEFAULT NULL, ct_user_id_id INT DEFAULT NULL, bl_numero VARCHAR(255) NOT NULL, bl_debut_numero INT NOT NULL, bl_fin_numero INT NOT NULL, bl_created_at DATE NOT NULL, bl_updated_at DATE DEFAULT NULL, ref_expr VARCHAR(255) DEFAULT NULL, date_ref_expr DATE DEFAULT NULL, bl_observation VARCHAR(255) DEFAULT NULL, INDEX IDX_334055EC36C2F638 (ct_centre_id_id), INDEX IDX_334055ECB1D04D41 (ct_imprime_tech_id_id), INDEX IDX_334055ECE4B6E02 (ct_user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_carte_grise (id INT AUTO_INCREMENT NOT NULL, ct_carrosserie_id_id INT DEFAULT NULL, ct_centre_id_id INT DEFAULT NULL, ct_source_energie_id_id INT DEFAULT NULL, ct_vehicule_id_id INT DEFAULT NULL, ct_user_id_id INT DEFAULT NULL, ct_zone_desserte_id_id INT DEFAULT NULL, cg_antecedant_id_id INT DEFAULT NULL, cg_date_emission DATE DEFAULT NULL, cg_nom VARCHAR(255) DEFAULT NULL, cg_prenom VARCHAR(255) DEFAULT NULL, cg_profession VARCHAR(255) DEFAULT NULL, cg_adresse VARCHAR(255) DEFAULT NULL, cg_phone VARCHAR(255) DEFAULT NULL, cg_nbr_assis INT NOT NULL, cg_nbr_debout INT NOT NULL, cg_puissance_admin INT NOT NULL, cg_mise_en_service DATE DEFAULT NULL, cg_patente VARCHAR(255) DEFAULT NULL, cg_ani VARCHAR(255) DEFAULT NULL, cg_rta VARCHAR(255) DEFAULT NULL, cg_num_carte_violette VARCHAR(255) DEFAULT NULL, cg_date_carte_violette DATE DEFAULT NULL, cg_lieu_carte_violette VARCHAR(255) DEFAULT NULL, cg_num_vignette VARCHAR(255) DEFAULT NULL, cg_date_vignette DATE DEFAULT NULL, cg_lieu_vignette VARCHAR(255) DEFAULT NULL, cg_immatriculation VARCHAR(255) NOT NULL, cg_created DATETIME NOT NULL, cg_nom_cooperative VARCHAR(255) DEFAULT NULL, cg_itineraire VARCHAR(255) DEFAULT NULL, cg_is_transport TINYINT(1) NOT NULL, cg_num_identification VARCHAR(255) DEFAULT NULL, cg_is_active TINYINT(1) NOT NULL, cg_observation VARCHAR(255) DEFAULT NULL, INDEX IDX_A447BE73C89A4997 (ct_carrosserie_id_id), INDEX IDX_A447BE7336C2F638 (ct_centre_id_id), INDEX IDX_A447BE73E926E299 (ct_source_energie_id_id), INDEX IDX_A447BE73A6F03AC4 (ct_vehicule_id_id), INDEX IDX_A447BE73E4B6E02 (ct_user_id_id), INDEX IDX_A447BE7311BAA3BA (ct_zone_desserte_id_id), INDEX IDX_A447BE73347AECB7 (cg_antecedant_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_vehicule (id INT AUTO_INCREMENT NOT NULL, ct_genre_id_id INT DEFAULT NULL, ct_marque_id_id INT DEFAULT NULL, vhc_cylindre DOUBLE PRECISION DEFAULT NULL, vhc_puissance DOUBLE PRECISION DEFAULT NULL, vhc_poids_vide DOUBLE PRECISION DEFAULT NULL, vhc_charge_utile DOUBLE PRECISION DEFAULT NULL, vhc_hauteur DOUBLE PRECISION DEFAULT NULL, vhc_largeur DOUBLE PRECISION DEFAULT NULL, vhc_longueur DOUBLE PRECISION DEFAULT NULL, vhc_num_serie VARCHAR(255) DEFAULT NULL, vhc_num_moteur VARCHAR(255) DEFAULT NULL, vhc_created DATETIME NOT NULL, vhc_provenance VARCHAR(255) DEFAULT NULL, vhc_type VARCHAR(255) DEFAULT NULL, vhc_poids_total_charge DOUBLE PRECISION DEFAULT NULL, INDEX IDX_BCF5CAE4BC603024 (ct_genre_id_id), INDEX IDX_BCF5CAE412EA7B94 (ct_marque_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_bordereau ADD CONSTRAINT FK_334055EC36C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_bordereau ADD CONSTRAINT FK_334055ECB1D04D41 FOREIGN KEY (ct_imprime_tech_id_id) REFERENCES ct_imprime_tech (id)');
        $this->addSql('ALTER TABLE ct_bordereau ADD CONSTRAINT FK_334055ECE4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE73C89A4997 FOREIGN KEY (ct_carrosserie_id_id) REFERENCES ct_carrosserie (id)');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE7336C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE73E926E299 FOREIGN KEY (ct_source_energie_id_id) REFERENCES ct_source_energie (id)');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE73A6F03AC4 FOREIGN KEY (ct_vehicule_id_id) REFERENCES ct_vehicule (id)');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE73E4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE7311BAA3BA FOREIGN KEY (ct_zone_desserte_id_id) REFERENCES ct_zone_desserte (id)');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE73347AECB7 FOREIGN KEY (cg_antecedant_id_id) REFERENCES ct_carte_grise (id)');
        $this->addSql('ALTER TABLE ct_vehicule ADD CONSTRAINT FK_BCF5CAE4BC603024 FOREIGN KEY (ct_genre_id_id) REFERENCES ct_genre (id)');
        $this->addSql('ALTER TABLE ct_vehicule ADD CONSTRAINT FK_BCF5CAE412EA7B94 FOREIGN KEY (ct_marque_id_id) REFERENCES ct_marque (id)');
        $this->addSql('ALTER TABLE ct_arrete_prix ADD art_observation VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_centre ADD centre_mere_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_centre ADD CONSTRAINT FK_902E42D71BF4F4B FOREIGN KEY (centre_mere_id) REFERENCES ct_centre (id)');
        $this->addSql('CREATE INDEX IDX_902E42D71BF4F4B ON ct_centre (centre_mere_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_bordereau DROP FOREIGN KEY FK_334055EC36C2F638');
        $this->addSql('ALTER TABLE ct_bordereau DROP FOREIGN KEY FK_334055ECB1D04D41');
        $this->addSql('ALTER TABLE ct_bordereau DROP FOREIGN KEY FK_334055ECE4B6E02');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE73C89A4997');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE7336C2F638');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE73E926E299');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE73A6F03AC4');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE73E4B6E02');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE7311BAA3BA');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE73347AECB7');
        $this->addSql('ALTER TABLE ct_vehicule DROP FOREIGN KEY FK_BCF5CAE4BC603024');
        $this->addSql('ALTER TABLE ct_vehicule DROP FOREIGN KEY FK_BCF5CAE412EA7B94');
        $this->addSql('DROP TABLE ct_bordereau');
        $this->addSql('DROP TABLE ct_carte_grise');
        $this->addSql('DROP TABLE ct_vehicule');
        $this->addSql('ALTER TABLE ct_arrete_prix DROP art_observation');
        $this->addSql('ALTER TABLE ct_centre DROP FOREIGN KEY FK_902E42D71BF4F4B');
        $this->addSql('DROP INDEX IDX_902E42D71BF4F4B ON ct_centre');
        $this->addSql('ALTER TABLE ct_centre DROP centre_mere_id');
    }
}
