<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240228074027 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_autre_vente ADD auv_num_pv VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_user CHANGE roles roles JSON NOT NULL COMMENT \'(DC2Type:json)\'');
        $this->addSql('ALTER TABLE ct_autre_vente DROP auv_num_pv');
    }
}
