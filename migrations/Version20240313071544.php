<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240313071544 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_carte_grise ADD ct_utilisation_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_carte_grise ADD CONSTRAINT FK_A447BE73A7D01DE7 FOREIGN KEY (ct_utilisation_id_id) REFERENCES ct_utilisation (id)');
        $this->addSql('CREATE INDEX IDX_A447BE73A7D01DE7 ON ct_carte_grise (ct_utilisation_id_id)');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE ct_carte_grise DROP FOREIGN KEY FK_A447BE73A7D01DE7');
        $this->addSql('DROP INDEX IDX_A447BE73A7D01DE7 ON ct_carte_grise');
        $this->addSql('ALTER TABLE ct_carte_grise DROP ct_utilisation_id_id');
    }
}
