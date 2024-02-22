<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217160028 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE defi (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, des VARCHAR(255) NOT NULL, nd VARCHAR(255) NOT NULL, statut VARCHAR(255) NOT NULL, exo_id INT NOT NULL, INDEX IDX_DCD5A35EDA1C6F33 (exo_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE defi ADD CONSTRAINT FK_DCD5A35EDA1C6F33 FOREIGN KEY (exo_id) REFERENCES exercice (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE defi DROP FOREIGN KEY FK_DCD5A35EDA1C6F33');
        $this->addSql('DROP TABLE defi');
    }
}
