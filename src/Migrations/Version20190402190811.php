<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402190811 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rabais DROP FOREIGN KEY FK_F6733D63EB576584');
        $this->addSql('DROP INDEX IDX_F6733D63EB576584 ON rabais');
        $this->addSql('ALTER TABLE rabais CHANGE rabais_produit_client_id rabais_produit_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rabais ADD CONSTRAINT FK_F6733D63C86E95EF FOREIGN KEY (rabais_produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_F6733D63C86E95EF ON rabais (rabais_produit_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE rabais DROP FOREIGN KEY FK_F6733D63C86E95EF');
        $this->addSql('DROP INDEX IDX_F6733D63C86E95EF ON rabais');
        $this->addSql('ALTER TABLE rabais CHANGE rabais_produit_id rabais_produit_client_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE rabais ADD CONSTRAINT FK_F6733D63EB576584 FOREIGN KEY (rabais_produit_client_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_F6733D63EB576584 ON rabais (rabais_produit_client_id)');
    }
}
