<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118124731 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_reception (id INT AUTO_INCREMENT NOT NULL, ct_centre_id_id INT DEFAULT NULL, ct_motif_id_id INT DEFAULT NULL, ct_type_reception_id_id INT DEFAULT NULL, ct_user_id_id INT DEFAULT NULL, ct_verificateur_id_id INT DEFAULT NULL, ct_utilisation_id_id INT DEFAULT NULL, ct_vehicule_id_id INT DEFAULT NULL, ct_source_energie_id_id INT DEFAULT NULL, ct_carrosserie_id_id INT DEFAULT NULL, ct_genre_id_id INT DEFAULT NULL, rcp_mise_service DATE DEFAULT NULL, rcp_immatriculation VARCHAR(255) DEFAULT NULL, rcp_proprietaire VARCHAR(255) DEFAULT NULL, rcp_profession VARCHAR(255) DEFAULT NULL, rcp_adresse VARCHAR(255) DEFAULT NULL, rcp_nbr_assis INT DEFAULT NULL, rcp_ngr_debout INT DEFAULT NULL, rcp_num_pv VARCHAR(255) DEFAULT NULL, rcp_num_group VARCHAR(255) DEFAULT NULL, rcp_created DATETIME DEFAULT NULL, rcp_is_active TINYINT(1) NOT NULL, rcp_genere INT NOT NULL, rcp_observation VARCHAR(255) DEFAULT NULL, INDEX IDX_942215A236C2F638 (ct_centre_id_id), INDEX IDX_942215A2603A0766 (ct_motif_id_id), INDEX IDX_942215A2AA1F8D61 (ct_type_reception_id_id), INDEX IDX_942215A2E4B6E02 (ct_user_id_id), INDEX IDX_942215A2E85C06CB (ct_verificateur_id_id), INDEX IDX_942215A2A7D01DE7 (ct_utilisation_id_id), INDEX IDX_942215A2A6F03AC4 (ct_vehicule_id_id), INDEX IDX_942215A2E926E299 (ct_source_energie_id_id), INDEX IDX_942215A2C89A4997 (ct_carrosserie_id_id), INDEX IDX_942215A2BC603024 (ct_genre_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A236C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2603A0766 FOREIGN KEY (ct_motif_id_id) REFERENCES ct_motif (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2AA1F8D61 FOREIGN KEY (ct_type_reception_id_id) REFERENCES ct_type_reception (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2E4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2E85C06CB FOREIGN KEY (ct_verificateur_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2A7D01DE7 FOREIGN KEY (ct_utilisation_id_id) REFERENCES ct_utilisation (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2A6F03AC4 FOREIGN KEY (ct_vehicule_id_id) REFERENCES ct_vehicule (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2E926E299 FOREIGN KEY (ct_source_energie_id_id) REFERENCES ct_source_energie (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2C89A4997 FOREIGN KEY (ct_carrosserie_id_id) REFERENCES ct_carrosserie (id)');
        $this->addSql('ALTER TABLE ct_reception ADD CONSTRAINT FK_942215A2BC603024 FOREIGN KEY (ct_genre_id_id) REFERENCES ct_genre (id)');
        $this->addSql('ALTER TABLE ct_const_av_ded ADD ct_user_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_const_av_ded ADD CONSTRAINT FK_5116CBDE4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('CREATE INDEX IDX_5116CBDE4B6E02 ON ct_const_av_ded (ct_user_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A236C2F638');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2603A0766');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2AA1F8D61');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2E4B6E02');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2E85C06CB');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2A7D01DE7');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2A6F03AC4');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2E926E299');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2C89A4997');
        $this->addSql('ALTER TABLE ct_reception DROP FOREIGN KEY FK_942215A2BC603024');
        $this->addSql('DROP TABLE ct_reception');
        $this->addSql('ALTER TABLE ct_const_av_ded DROP FOREIGN KEY FK_5116CBDE4B6E02');
        $this->addSql('DROP INDEX IDX_5116CBDE4B6E02 ON ct_const_av_ded');
        $this->addSql('ALTER TABLE ct_const_av_ded DROP ct_user_id_id');
    }
}
