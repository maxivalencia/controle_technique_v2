<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231118081103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_historique (id INT AUTO_INCREMENT NOT NULL, ct_user_id_id INT NOT NULL, ct_center_id_id INT DEFAULT NULL, ct_historique_type_id_id INT DEFAULT NULL, hst_description VARCHAR(255) NOT NULL, hst_date_created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', hst_is_view INT NOT NULL, INDEX IDX_7E72DEE1E4B6E02 (ct_user_id_id), INDEX IDX_7E72DEE1E2C56C4A (ct_center_id_id), INDEX IDX_7E72DEE1D2EF42B4 (ct_historique_type_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_historique ADD CONSTRAINT FK_7E72DEE1E4B6E02 FOREIGN KEY (ct_user_id_id) REFERENCES ct_user (id)');
        $this->addSql('ALTER TABLE ct_historique ADD CONSTRAINT FK_7E72DEE1E2C56C4A FOREIGN KEY (ct_center_id_id) REFERENCES ct_centre (id)');
        $this->addSql('ALTER TABLE ct_historique ADD CONSTRAINT FK_7E72DEE1D2EF42B4 FOREIGN KEY (ct_historique_type_id_id) REFERENCES ct_historique_type (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_historique DROP FOREIGN KEY FK_7E72DEE1E4B6E02');
        $this->addSql('ALTER TABLE ct_historique DROP FOREIGN KEY FK_7E72DEE1E2C56C4A');
        $this->addSql('ALTER TABLE ct_historique DROP FOREIGN KEY FK_7E72DEE1D2EF42B4');
        $this->addSql('DROP TABLE ct_historique');
    }
}
