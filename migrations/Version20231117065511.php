<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231117065511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_arrete_prix (id INT AUTO_INCREMENT NOT NULL, ct_user_id_id INT DEFAULT NULL, art_numero VARCHAR(255) NOT NULL, art_date DATE DEFAULT NULL, art_date_application DATE DEFAULT NULL, art_created_at DATE NOT NULL, art_updated_at DATE DEFAULT NULL, INDEX IDX_1CEB5E02E4B6E02 (ct_user_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_droit_ptac (id INT AUTO_INCREMENT NOT NULL, ct_genre_categorie_id_id INT DEFAULT NULL, ct_type_droit_ptac_id_id INT DEFAULT NULL, ct_arrete_prix_id_id INT DEFAULT NULL, dp_prix_min DOUBLE PRECISION DEFAULT NULL, dp_prix_max DOUBLE PRECISION DEFAULT NULL, dp_droit DOUBLE PRECISION NOT NULL, INDEX IDX_DB918ADA3BEDFDD8 (ct_genre_categorie_id_id), INDEX IDX_DB918ADA5FFCB50 (ct_type_droit_ptac_id_id), INDEX IDX_DB918ADA2BD53E47 (ct_arrete_prix_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_arrete_prix ADD CONSTRAINT FK_1CEB5E02E4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_droit_ptac ADD CONSTRAINT FK_DB918ADA3BEDFDD8 FOREIGN KEY (ct_genre_categorie_id_id) REFERENCES ct_genre_categorie (id)');
        $this->addSql('ALTER TABLE ct_droit_ptac ADD CONSTRAINT FK_DB918ADA5FFCB50 FOREIGN KEY (ct_type_droit_ptac_id_id) REFERENCES ct_type_droit_ptac (id)');
        $this->addSql('ALTER TABLE ct_droit_ptac ADD CONSTRAINT FK_DB918ADA2BD53E47 FOREIGN KEY (ct_arrete_prix_id_id) REFERENCES ct_arrete_prix (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_arrete_prix DROP FOREIGN KEY FK_1CEB5E02E4B6E02');
        $this->addSql('ALTER TABLE ct_droit_ptac DROP FOREIGN KEY FK_DB918ADA3BEDFDD8');
        $this->addSql('ALTER TABLE ct_droit_ptac DROP FOREIGN KEY FK_DB918ADA5FFCB50');
        $this->addSql('ALTER TABLE ct_droit_ptac DROP FOREIGN KEY FK_DB918ADA2BD53E47');
        $this->addSql('DROP TABLE ct_arrete_prix');
        $this->addSql('DROP TABLE ct_droit_ptac');
    }
}
