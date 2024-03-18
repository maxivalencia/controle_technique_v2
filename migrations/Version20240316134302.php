<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240316134302 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_autre_vente ADD ct_utilisation_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_autre_vente ADD CONSTRAINT FK_BD5B077BA7D01DE7 FOREIGN KEY (ct_utilisation_id_id) REFERENCES ct_utilisation (id)');
        $this->addSql('CREATE INDEX IDX_BD5B077BA7D01DE7 ON ct_autre_vente (ct_utilisation_id_id)');
        $this->addSql('ALTER TABLE ct_const_av_ded ADD ct_utilisation_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_const_av_ded ADD CONSTRAINT FK_5116CBDA7D01DE7 FOREIGN KEY (ct_utilisation_id_id) REFERENCES ct_utilisation (id)');
        $this->addSql('CREATE INDEX IDX_5116CBDA7D01DE7 ON ct_const_av_ded (ct_utilisation_id_id)');
        $this->addSql('ALTER TABLE ct_droit_ptac ADD ct_type_reception_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_droit_ptac ADD CONSTRAINT FK_DB918ADAAA1F8D61 FOREIGN KEY (ct_type_reception_id_id) REFERENCES ct_type_reception (id)');
        $this->addSql('CREATE INDEX IDX_DB918ADAAA1F8D61 ON ct_droit_ptac (ct_type_reception_id_id)');
        $this->addSql('ALTER TABLE ct_imprime_tech_use ADD ct_utilisation_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_imprime_tech_use ADD CONSTRAINT FK_ACFCEC1CA7D01DE7 FOREIGN KEY (ct_utilisation_id_id) REFERENCES ct_utilisation (id)');
        $this->addSql('CREATE INDEX IDX_ACFCEC1CA7D01DE7 ON ct_imprime_tech_use (ct_utilisation_id_id)');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE ct_const_av_ded DROP FOREIGN KEY FK_5116CBDA7D01DE7');
        $this->addSql('DROP INDEX IDX_5116CBDA7D01DE7 ON ct_const_av_ded');
        $this->addSql('ALTER TABLE ct_const_av_ded DROP ct_utilisation_id_id');
        $this->addSql('ALTER TABLE ct_imprime_tech_use DROP FOREIGN KEY FK_ACFCEC1CA7D01DE7');
        $this->addSql('DROP INDEX IDX_ACFCEC1CA7D01DE7 ON ct_imprime_tech_use');
        $this->addSql('ALTER TABLE ct_imprime_tech_use DROP ct_utilisation_id_id');
        $this->addSql('ALTER TABLE ct_autre_vente DROP FOREIGN KEY FK_BD5B077BA7D01DE7');
        $this->addSql('DROP INDEX IDX_BD5B077BA7D01DE7 ON ct_autre_vente');
        $this->addSql('ALTER TABLE ct_autre_vente DROP ct_utilisation_id_id');
        $this->addSql('ALTER TABLE ct_droit_ptac DROP FOREIGN KEY FK_DB918ADAAA1F8D61');
        $this->addSql('DROP INDEX IDX_DB918ADAAA1F8D61 ON ct_droit_ptac');
        $this->addSql('ALTER TABLE ct_droit_ptac DROP ct_type_reception_id_id');
    }
}
