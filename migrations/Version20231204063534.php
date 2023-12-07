<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231204063534 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_user ADD ct_role_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE ct_user ADD CONSTRAINT FK_A115979E1B557D75 FOREIGN KEY (ct_role_id_id) REFERENCES ct_role (id)');
        $this->addSql('CREATE INDEX IDX_A115979E1B557D75 ON ct_user (ct_role_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_user DROP FOREIGN KEY FK_A115979E1B557D75');
        $this->addSql('DROP INDEX IDX_A115979E1B557D75 ON ct_user');
        $this->addSql('ALTER TABLE ct_user DROP ct_role_id_id');
    }
}
