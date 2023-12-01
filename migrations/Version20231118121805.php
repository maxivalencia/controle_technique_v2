<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118121805 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_imprime_tech_use (id INT AUTO_INCREMENT NOT NULL, ct_bordereau_id_id INT DEFAULT NULL, ct_centre_id_id INT DEFAULT NULL, ct_imprime_tech_id_id INT DEFAULT NULL, ct_user_id_id INT DEFAULT NULL, ct_usage_it_id_id INT DEFAULT NULL, ct_controle_id INT DEFAULT NULL, itu_numero INT DEFAULT NULL, itu_used TINYINT(1) NOT NULL, actived_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', itu_observation VARCHAR(255) DEFAULT NULL, itu_is_visible TINYINT(1) NOT NULL, INDEX IDX_ACFCEC1CD930EA7D (ct_bordereau_id_id), INDEX IDX_ACFCEC1C36C2F638 (ct_centre_id_id), INDEX IDX_ACFCEC1CB1D04D41 (ct_imprime_tech_id_id), INDEX IDX_ACFCEC1CE4B6E02 (ct_user_id_id), INDEX IDX_ACFCEC1C922496EB (ct_usage_it_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_imprime_tech_use ADD CONSTRAINT FK_ACFCEC1CD930EA7D FOREIGN KEY (ct_bordereau_id_id) REFERENCES ct_bordereau (id)');
        $this->addSql('ALTER TABLE ct_imprime_tech_use ADD CONSTRAINT FK_ACFCEC1C36C2F638 FOREIGN KEY (ct_centre_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_imprime_tech_use ADD CONSTRAINT FK_ACFCEC1CB1D04D41 FOREIGN KEY (ct_imprime_tech_id_id) REFERENCES ct_imprime_tech (id)');
        $this->addSql('ALTER TABLE ct_imprime_tech_use ADD CONSTRAINT FK_ACFCEC1CE4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_imprime_tech_use ADD CONSTRAINT FK_ACFCEC1C922496EB FOREIGN KEY (ct_usage_it_id_id) REFERENCES ct_usage_imprime_technique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_imprime_tech_use DROP FOREIGN KEY FK_ACFCEC1CD930EA7D');
        $this->addSql('ALTER TABLE ct_imprime_tech_use DROP FOREIGN KEY FK_ACFCEC1C36C2F638');
        $this->addSql('ALTER TABLE ct_imprime_tech_use DROP FOREIGN KEY FK_ACFCEC1CB1D04D41');
        $this->addSql('ALTER TABLE ct_imprime_tech_use DROP FOREIGN KEY FK_ACFCEC1CE4B6E02');
        $this->addSql('ALTER TABLE ct_imprime_tech_use DROP FOREIGN KEY FK_ACFCEC1C922496EB');
        $this->addSql('DROP TABLE ct_imprime_tech_use');
    }
}
