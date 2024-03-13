<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313122801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_imprime_tech ADD CONSTRAINT FK_3F49AE42C850688F FOREIGN KEY (ct_type_imprime_id_id) REFERENCES ct_type_imprime (id)');
        $this->addSql('CREATE INDEX IDX_3F49AE42C850688F ON ct_imprime_tech (ct_type_imprime_id_id)');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE ct_imprime_tech DROP FOREIGN KEY FK_3F49AE42C850688F');
        $this->addSql('DROP INDEX IDX_3F49AE42C850688F ON ct_imprime_tech');
    }
}
