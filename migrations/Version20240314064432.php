<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314064432 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_visite_ct_imprime_tech (ct_visite_id INT NOT NULL, ct_imprime_tech_id INT NOT NULL, INDEX IDX_152E64805314CD4 (ct_visite_id), INDEX IDX_152E64802ADBF4F2 (ct_imprime_tech_id), PRIMARY KEY(ct_visite_id, ct_imprime_tech_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_visite_ct_imprime_tech ADD CONSTRAINT FK_152E64805314CD4 FOREIGN KEY (ct_visite_id) REFERENCES ct_visite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_visite_ct_imprime_tech ADD CONSTRAINT FK_152E64802ADBF4F2 FOREIGN KEY (ct_imprime_tech_id) REFERENCES ct_imprime_tech (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ct_imprime_tech ADD CONSTRAINT FK_3F49AE42C850688F FOREIGN KEY (ct_type_imprime_id_id) REFERENCES ct_type_imprime (id)');
        $this->addSql('CREATE INDEX IDX_3F49AE42C850688F ON ct_imprime_tech (ct_type_imprime_id_id)');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_visite_ct_imprime_tech DROP FOREIGN KEY FK_152E64805314CD4');
        $this->addSql('ALTER TABLE ct_visite_ct_imprime_tech DROP FOREIGN KEY FK_152E64802ADBF4F2');
        $this->addSql('DROP TABLE ct_visite_ct_imprime_tech');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE ct_imprime_tech DROP FOREIGN KEY FK_3F49AE42C850688F');
        $this->addSql('DROP INDEX IDX_3F49AE42C850688F ON ct_imprime_tech');
    }
}
