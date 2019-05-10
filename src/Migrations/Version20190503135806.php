<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503135806 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE le_panier ADD id_panier_id INT DEFAULT NULL, ADD id_produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE le_panier ADD CONSTRAINT FK_84C6FDCE77482E5B FOREIGN KEY (id_panier_id) REFERENCES panier (id)');
        $this->addSql('ALTER TABLE le_panier ADD CONSTRAINT FK_84C6FDCEAABEFE2C FOREIGN KEY (id_produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_84C6FDCE77482E5B ON le_panier (id_panier_id)');
        $this->addSql('CREATE INDEX IDX_84C6FDCEAABEFE2C ON le_panier (id_produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE le_panier DROP FOREIGN KEY FK_84C6FDCE77482E5B');
        $this->addSql('ALTER TABLE le_panier DROP FOREIGN KEY FK_84C6FDCEAABEFE2C');
        $this->addSql('DROP INDEX IDX_84C6FDCE77482E5B ON le_panier');
        $this->addSql('DROP INDEX IDX_84C6FDCEAABEFE2C ON le_panier');
        $this->addSql('ALTER TABLE le_panier DROP id_panier_id, DROP id_produit_id');
    }
}
