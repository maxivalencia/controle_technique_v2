<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207092236 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_autre_vente_ct_visite_extra (ct_autre_vente_id INT NOT NULL, ct_visite_extra_id INT NOT NULL, INDEX IDX_38C3E3BC8D1D5C87 (ct_autre_vente_id), INDEX IDX_38C3E3BC15D88434 (ct_visite_extra_id), PRIMARY KEY(ct_autre_vente_id, ct_visite_extra_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_autre_vente_ct_visite_extra ADD CONSTRAINT FK_38C3E3BC8D1D5C87 FOREIGN KEY (ct_autre_vente_id) REFERENCES ct_autre_vente (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_autre_vente_ct_visite_extra ADD CONSTRAINT FK_38C3E3BC15D88434 FOREIGN KEY (ct_visite_extra_id) REFERENCES ct_visite_extra (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE ct_autre_donne');
        $this->addSql('ALTER TABLE ct_extra_vente CHANGE exv_created_at exv_created_at DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_autre_donne (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, attribut VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ct_autre_vente_ct_visite_extra DROP FOREIGN KEY FK_38C3E3BC8D1D5C87');
        $this->addSql('ALTER TABLE ct_autre_vente_ct_visite_extra DROP FOREIGN KEY FK_38C3E3BC15D88434');
        $this->addSql('DROP TABLE ct_autre_vente_ct_visite_extra');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE ct_extra_vente CHANGE exv_created_at exv_created_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
