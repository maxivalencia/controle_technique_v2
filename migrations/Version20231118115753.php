<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118115753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_const_av_ded (id INT AUTO_INCREMENT NOT NULL, ct_centre_id_id INT DEFAULT NULL, ct_verificateur_id_id INT DEFAULT NULL, cad_provenance VARCHAR(255) DEFAULT NULL, cad_divers VARCHAR(255) DEFAULT NULL, cad_proprietaire_nom VARCHAR(255) DEFAULT NULL, cad_proprietaire_adresse VARCHAR(255) DEFAULT NULL, cad_bon_etat TINYINT(1) NOT NULL, cad_sec_pers TINYINT(1) NOT NULL, cad_sec_march TINYINT(1) NOT NULL, cad_protec_env TINYINT(1) NOT NULL, cad_numero VARCHAR(255) DEFAULT NULL, cad_immatriculation VARCHAR(255) DEFAULT NULL, cad_date_embarquement DATE DEFAULT NULL, cad_lieu_embarquement VARCHAR(255) DEFAULT NULL, cad_created DATETIME NOT NULL, cad_observation VARCHAR(255) DEFAULT NULL, cad_conforme TINYINT(1) NOT NULL, cad_is_active TINYINT(1) NOT NULL, cad_genere INT NOT NULL, INDEX IDX_5116CBD36C2F638 (ct_centre_id_id), INDEX IDX_5116CBDE85C06CB (ct_verificateur_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_const_av_ded_ct_const_av_ded_carac (ct_const_av_ded_id INT NOT NULL, ct_const_av_ded_carac_id INT NOT NULL, INDEX IDX_DF8A0DAB83FAC2F (ct_const_av_ded_id), INDEX IDX_DF8A0DABC567580F (ct_const_av_ded_carac_id), PRIMARY KEY(ct_const_av_ded_id, ct_const_av_ded_carac_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_const_av_ded_carac (id INT AUTO_INCREMENT NOT NULL, ct_carrosserie_id_id INT DEFAULT NULL, ct_const_av_ded_type_id_id INT DEFAULT NULL, ct_genre_id_id INT DEFAULT NULL, ct_marque_id_id INT DEFAULT NULL, ct_source_energie_id_id INT DEFAULT NULL, cad_cylindre DOUBLE PRECISION DEFAULT NULL, cad_puissance DOUBLE PRECISION DEFAULT NULL, cad_poids_vide DOUBLE PRECISION DEFAULT NULL, cad_charge_utile DOUBLE PRECISION DEFAULT NULL, cad_hauteur DOUBLE PRECISION DEFAULT NULL, cad_largeur DOUBLE PRECISION DEFAULT NULL, cad_longueur DOUBLE PRECISION DEFAULT NULL, cad_num_serie_type VARCHAR(255) DEFAULT NULL, cad_num_moteur VARCHAR(255) DEFAULT NULL, cad_type_car VARCHAR(255) DEFAULT NULL, cad_poids_maxima LONGTEXT DEFAULT NULL, cad_poids_total_charge DOUBLE PRECISION DEFAULT NULL, cad_premiere_circule VARCHAR(255) DEFAULT NULL, cad_nbr_assis INT DEFAULT NULL, INDEX IDX_FAC238B6C89A4997 (ct_carrosserie_id_id), INDEX IDX_FAC238B6BE9C66F4 (ct_const_av_ded_type_id_id), INDEX IDX_FAC238B6BC603024 (ct_genre_id_id), INDEX IDX_FAC238B612EA7B94 (ct_marque_id_id), INDEX IDX_FAC238B6E926E299 (ct_source_energie_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_const_av_ded ADD CONSTRAINT FK_5116CBD36C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_const_av_ded ADD CONSTRAINT FK_5116CBDE85C06CB FOREIGN KEY (ct_verificateur_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_const_av_ded_ct_const_av_ded_carac ADD CONSTRAINT FK_DF8A0DAB83FAC2F FOREIGN KEY (ct_const_av_ded_id) REFERENCES ct_const_av_ded (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_const_av_ded_ct_const_av_ded_carac ADD CONSTRAINT FK_DF8A0DABC567580F FOREIGN KEY (ct_const_av_ded_carac_id) REFERENCES ct_const_av_ded_carac (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac ADD CONSTRAINT FK_FAC238B6C89A4997 FOREIGN KEY (ct_carrosserie_id_id) REFERENCES ct_carrosserie (id)');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac ADD CONSTRAINT FK_FAC238B6BE9C66F4 FOREIGN KEY (ct_const_av_ded_type_id_id) REFERENCES ct_const_av_ded_type (id)');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac ADD CONSTRAINT FK_FAC238B6BC603024 FOREIGN KEY (ct_genre_id_id) REFERENCES ct_genre (id)');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac ADD CONSTRAINT FK_FAC238B612EA7B94 FOREIGN KEY (ct_marque_id_id) REFERENCES ct_marque (id)');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac ADD CONSTRAINT FK_FAC238B6E926E299 FOREIGN KEY (ct_source_energie_id_id) REFERENCES ct_source_energie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_const_av_ded DROP FOREIGN KEY FK_5116CBD36C2F638');
        $this->addSql('ALTER TABLE ct_const_av_ded DROP FOREIGN KEY FK_5116CBDE85C06CB');
        $this->addSql('ALTER TABLE ct_const_av_ded_ct_const_av_ded_carac DROP FOREIGN KEY FK_DF8A0DAB83FAC2F');
        $this->addSql('ALTER TABLE ct_const_av_ded_ct_const_av_ded_carac DROP FOREIGN KEY FK_DF8A0DABC567580F');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac DROP FOREIGN KEY FK_FAC238B6C89A4997');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac DROP FOREIGN KEY FK_FAC238B6BE9C66F4');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac DROP FOREIGN KEY FK_FAC238B6BC603024');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac DROP FOREIGN KEY FK_FAC238B612EA7B94');
        $this->addSql('ALTER TABLE ct_const_av_ded_carac DROP FOREIGN KEY FK_FAC238B6E926E299');
        $this->addSql('DROP TABLE ct_const_av_ded');
        $this->addSql('DROP TABLE ct_const_av_ded_ct_const_av_ded_carac');
        $this->addSql('DROP TABLE ct_const_av_ded_carac');
    }
}
