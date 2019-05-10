<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190328151432 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27F0164A0');
        $this->addSql('DROP INDEX IDX_29A5EC27F0164A0 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE id_rabais_id rabais_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC278F34D887 FOREIGN KEY (rabais_id) REFERENCES rabais (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC278F34D887 ON produit (rabais_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC278F34D887');
        $this->addSql('DROP INDEX IDX_29A5EC278F34D887 ON produit');
        $this->addSql('ALTER TABLE produit CHANGE rabais_id id_rabais_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27F0164A0 FOREIGN KEY (id_rabais_id) REFERENCES rabais (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC27F0164A0 ON produit (id_rabais_id)');
    }
}
