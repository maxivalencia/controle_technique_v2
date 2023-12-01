<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231115074522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ct_carrosserie (id INT AUTO_INCREMENT NOT NULL, crs_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_const_av_ded_type (id INT AUTO_INCREMENT NOT NULL, cad_tp_libelle VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_genre (id INT AUTO_INCREMENT NOT NULL, ct_genre_categorie_id_id INT DEFAULT NULL, gr_libelle VARCHAR(255) NOT NULL, gr_code VARCHAR(255) NOT NULL, INDEX IDX_9BCBF2CE3BEDFDD8 (ct_genre_categorie_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ct_genre_categorie (id INT AUTO_INCREMENT NOT NULL, gc_libelle VARCHAR(255) NOT NULL, gc_is_calculable TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ct_genre ADD CONSTRAINT FK_9BCBF2CE3BEDFDD8 FOREIGN KEY (ct_genre_categorie_id_id) REFERENCES ct_genre_categorie (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ct_genre DROP FOREIGN KEY FK_9BCBF2CE3BEDFDD8');
        $this->addSql('DROP TABLE ct_carrosserie');
        $this->addSql('DROP TABLE ct_const_av_ded_type');
        $this->addSql('DROP TABLE ct_genre');
        $this->addSql('DROP TABLE ct_genre_categorie');
    }
}
