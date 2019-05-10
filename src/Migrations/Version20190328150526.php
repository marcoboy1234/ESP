<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328150526 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE rabais (id INT AUTO_INCREMENT NOT NULL, date_de_debut DATE NOT NULL, date_de_fin DATE NOT NULL, rabais DOUBLE PRECISION NOT NULL, employe TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE produit ADD id_rabais_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27F0164A0 FOREIGN KEY (id_rabais_id) REFERENCES rabais (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27F0164A0 ON produit (id_rabais_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27F0164A0');
        $this->addSql('DROP TABLE rabais');
        $this->addSql('DROP INDEX IDX_29A5EC27F0164A0 ON produit');
        $this->addSql('ALTER TABLE produit DROP id_rabais_id');
    }
}
