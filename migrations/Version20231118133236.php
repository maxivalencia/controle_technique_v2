<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118133236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_autre_vente (id INT AUTO_INCREMENT NOT NULL, ct_usage_it_id INT DEFAULT NULL, ct_autre_tarif_id_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, verificateur_id_id INT DEFAULT NULL, ct_carte_grise_id_id INT DEFAULT NULL, ct_centre_id_id INT DEFAULT NULL, auv_is_visible TINYINT(1) NOT NULL, auv_created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', controle_id INT DEFAULT NULL, INDEX IDX_BD5B077BDBB11B80 (ct_usage_it_id), INDEX IDX_BD5B077B3A162080 (ct_autre_tarif_id_id), INDEX IDX_BD5B077B9D86650F (user_id_id), INDEX IDX_BD5B077B4A8E174A (verificateur_id_id), INDEX IDX_BD5B077BF8F2EE9 (ct_carte_grise_id_id), INDEX IDX_BD5B077B36C2F638 (ct_centre_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_extra_vente (id INT AUTO_INCREMENT NOT NULL, ct_visite_id_id INT DEFAULT NULL, ct_visite_extra_id_id INT DEFAULT NULL, exv_created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', exv_is_active TINYINT(1) NOT NULL, INDEX IDX_C33DB5AE1D358EA (ct_visite_id_id), INDEX IDX_C33DB5A92C48FC0 (ct_visite_extra_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_photo (id INT AUTO_INCREMENT NOT NULL, ct_usage_it_id INT DEFAULT NULL, ct_controle_id INT DEFAULT NULL, pht_nom VARCHAR(255) DEFAULT NULL, pht_dossier VARCHAR(255) DEFAULT NULL, INDEX IDX_C2C452EDBB11B80 (ct_usage_it_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_visite (id INT AUTO_INCREMENT NOT NULL, ct_carte_grise_id_id INT DEFAULT NULL, ct_centre_id_id INT DEFAULT NULL, ct_type_visite_id_id INT DEFAULT NULL, ct_usage_id_id INT DEFAULT NULL, ct_user_id_id INT DEFAULT NULL, ct_verificateur_id_id INT DEFAULT NULL, ct_utilisation_id_id INT DEFAULT NULL, vst_num_pv VARCHAR(255) DEFAULT NULL, vst_num_feuille_caisse VARCHAR(255) DEFAULT NULL, vst_date_expiration DATE DEFAULT NULL, vst_created DATETIME DEFAULT NULL, vst_updated DATETIME DEFAULT NULL, vst_is_apte TINYINT(1) NOT NULL, vst_is_contre_visite TINYINT(1) NOT NULL, vst_duree_reparation VARCHAR(255) DEFAULT NULL, vst_is_active TINYINT(1) NOT NULL, vst_genere INT DEFAULT NULL, vst_observation VARCHAR(255) DEFAULT NULL, INDEX IDX_7F3E82E3F8F2EE9 (ct_carte_grise_id_id), INDEX IDX_7F3E82E336C2F638 (ct_centre_id_id), INDEX IDX_7F3E82E3F18C25C4 (ct_type_visite_id_id), INDEX IDX_7F3E82E38BA84C1F (ct_usage_id_id), INDEX IDX_7F3E82E3E4B6E02 (ct_user_id_id), INDEX IDX_7F3E82E3E85C06CB (ct_verificateur_id_id), INDEX IDX_7F3E82E3A7D01DE7 (ct_utilisation_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_visite_ct_anomalie (ct_visite_id INT NOT NULL, ct_anomalie_id INT NOT NULL, INDEX IDX_F39BC5525314CD4 (ct_visite_id), INDEX IDX_F39BC552D0C80021 (ct_anomalie_id), PRIMARY KEY(ct_visite_id, ct_anomalie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_visite_ct_visite_extra (ct_visite_id INT NOT NULL, ct_visite_extra_id INT NOT NULL, INDEX IDX_F9CF011A5314CD4 (ct_visite_id), INDEX IDX_F9CF011A15D88434 (ct_visite_extra_id), PRIMARY KEY(ct_visite_id, ct_visite_extra_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_autre_vente ADD CONSTRAINT FK_BD5B077BDBB11B80 FOREIGN KEY (ct_usage_it_id) REFERENCES ct_usage_imprime_technique (id)');
        $this->addSql('ALTER TABLE ct_autre_vente ADD CONSTRAINT FK_BD5B077B3A162080 FOREIGN KEY (ct_autre_tarif_id_id) REFERENCES ct_autre_tarif (id)');
        $this->addSql('ALTER TABLE ct_autre_vente ADD CONSTRAINT FK_BD5B077B9D86650F FOREIGN KEY (user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_autre_vente ADD CONSTRAINT FK_BD5B077B4A8E174A FOREIGN KEY (verificateur_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_autre_vente ADD CONSTRAINT FK_BD5B077BF8F2EE9 FOREIGN KEY (ct_carte_grise_id_id) REFERENCES ct_carte_grise (id)');
        $this->addSql('ALTER TABLE ct_autre_vente ADD CONSTRAINT FK_BD5B077B36C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_extra_vente ADD CONSTRAINT FK_C33DB5AE1D358EA FOREIGN KEY (ct_visite_id_id) REFERENCES ct_visite (id)');
        $this->addSql('ALTER TABLE ct_extra_vente ADD CONSTRAINT FK_C33DB5A92C48FC0 FOREIGN KEY (ct_visite_extra_id_id) REFERENCES ct_visite_extra (id)');
        $this->addSql('ALTER TABLE ct_photo ADD CONSTRAINT FK_C2C452EDBB11B80 FOREIGN KEY (ct_usage_it_id) REFERENCES ct_usage_imprime_technique (id)');
        $this->addSql('ALTER TABLE ct_visite ADD CONSTRAINT FK_7F3E82E3F8F2EE9 FOREIGN KEY (ct_carte_grise_id_id) REFERENCES ct_carte_grise (id)');
        $this->addSql('ALTER TABLE ct_visite ADD CONSTRAINT FK_7F3E82E336C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_visite ADD CONSTRAINT FK_7F3E82E3F18C25C4 FOREIGN KEY (ct_type_visite_id_id) REFERENCES ct_type_visite (id)');
        $this->addSql('ALTER TABLE ct_visite ADD CONSTRAINT FK_7F3E82E38BA84C1F FOREIGN KEY (ct_usage_id_id) REFERENCES ct_usage (id)');
        $this->addSql('ALTER TABLE ct_visite ADD CONSTRAINT FK_7F3E82E3E4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_visite ADD CONSTRAINT FK_7F3E82E3E85C06CB FOREIGN KEY (ct_verificateur_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_visite ADD CONSTRAINT FK_7F3E82E3A7D01DE7 FOREIGN KEY (ct_utilisation_id_id) REFERENCES ct_utilisation (id)');
        $this->addSql('ALTER TABLE ct_visite_ct_anomalie ADD CONSTRAINT FK_F39BC5525314CD4 FOREIGN KEY (ct_visite_id) REFERENCES ct_visite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_visite_ct_anomalie ADD CONSTRAINT FK_F39BC552D0C80021 FOREIGN KEY (ct_anomalie_id) REFERENCES ct_anomalie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_visite_ct_visite_extra ADD CONSTRAINT FK_F9CF011A5314CD4 FOREIGN KEY (ct_visite_id) REFERENCES ct_visite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_visite_ct_visite_extra ADD CONSTRAINT FK_F9CF011A15D88434 FOREIGN KEY (ct_visite_extra_id) REFERENCES ct_visite_extra (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_user ADD ct_centre_id_id INT DEFAULT NULL, ADD usr_enable TINYINT(1) NOT NULL, ADD usr_last_login DATETIME DEFAULT NULL, ADD usr_mail VARCHAR(255) DEFAULT NULL, ADD usr_nom VARCHAR(255) DEFAULT NULL, ADD usr_adresse VARCHAR(255) DEFAULT NULL, ADD usr_created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD usr_updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD usr_telephone VARCHAR(255) DEFAULT NULL, ADD usr_nbr_connexion INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_user ADD CONSTRAINT FK_A115979E36C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('CREATE INDEX IDX_A115979E36C2F638 ON ct_user (ct_centre_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_autre_vente DROP FOREIGN KEY FK_BD5B077BDBB11B80');
        $this->addSql('ALTER TABLE ct_autre_vente DROP FOREIGN KEY FK_BD5B077B3A162080');
        $this->addSql('ALTER TABLE ct_autre_vente DROP FOREIGN KEY FK_BD5B077B9D86650F');
        $this->addSql('ALTER TABLE ct_autre_vente DROP FOREIGN KEY FK_BD5B077B4A8E174A');
        $this->addSql('ALTER TABLE ct_autre_vente DROP FOREIGN KEY FK_BD5B077BF8F2EE9');
        $this->addSql('ALTER TABLE ct_autre_vente DROP FOREIGN KEY FK_BD5B077B36C2F638');
        $this->addSql('ALTER TABLE ct_extra_vente DROP FOREIGN KEY FK_C33DB5AE1D358EA');
        $this->addSql('ALTER TABLE ct_extra_vente DROP FOREIGN KEY FK_C33DB5A92C48FC0');
        $this->addSql('ALTER TABLE ct_photo DROP FOREIGN KEY FK_C2C452EDBB11B80');
        $this->addSql('ALTER TABLE ct_visite DROP FOREIGN KEY FK_7F3E82E3F8F2EE9');
        $this->addSql('ALTER TABLE ct_visite DROP FOREIGN KEY FK_7F3E82E336C2F638');
        $this->addSql('ALTER TABLE ct_visite DROP FOREIGN KEY FK_7F3E82E3F18C25C4');
        $this->addSql('ALTER TABLE ct_visite DROP FOREIGN KEY FK_7F3E82E38BA84C1F');
        $this->addSql('ALTER TABLE ct_visite DROP FOREIGN KEY FK_7F3E82E3E4B6E02');
        $this->addSql('ALTER TABLE ct_visite DROP FOREIGN KEY FK_7F3E82E3E85C06CB');
        $this->addSql('ALTER TABLE ct_visite DROP FOREIGN KEY FK_7F3E82E3A7D01DE7');
        $this->addSql('ALTER TABLE ct_visite_ct_anomalie DROP FOREIGN KEY FK_F39BC5525314CD4');
        $this->addSql('ALTER TABLE ct_visite_ct_anomalie DROP FOREIGN KEY FK_F39BC552D0C80021');
        $this->addSql('ALTER TABLE ct_visite_ct_visite_extra DROP FOREIGN KEY FK_F9CF011A5314CD4');
        $this->addSql('ALTER TABLE ct_visite_ct_visite_extra DROP FOREIGN KEY FK_F9CF011A15D88434');
        $this->addSql('DROP TABLE ct_autre_vente');
        $this->addSql('DROP TABLE ct_extra_vente');
        $this->addSql('DROP TABLE ct_photo');
        $this->addSql('DROP TABLE ct_visite');
        $this->addSql('DROP TABLE ct_visite_ct_anomalie');
        $this->addSql('DROP TABLE ct_visite_ct_visite_extra');
        $this->addSql('ALTER TABLE ct_user DROP FOREIGN KEY FK_A115979E36C2F638');
        $this->addSql('DROP INDEX IDX_A115979E36C2F638 ON ct_user');
        $this->addSql('ALTER TABLE ct_user DROP ct_centre_id_id, DROP usr_enable, DROP usr_last_login, DROP usr_mail, DROP usr_nom, DROP usr_adresse, DROP usr_created_at, DROP usr_updated_at, DROP usr_telephone, DROP usr_nbr_connexion');
    }
}
