<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305173037 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE defi (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, des VARCHAR(255) NOT NULL, nd VARCHAR(255) NOT NULL, nbj INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE defi_exercice (defi_id INT NOT NULL, exercice_id INT NOT NULL, INDEX IDX_C88D081073F00F27 (defi_id), INDEX IDX_C88D081089D40298 (exercice_id), PRIMARY KEY(defi_id, exercice_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE exercice (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, des LONGTEXT NOT NULL, mc VARCHAR(255) NOT NULL, nd VARCHAR(255) NOT NULL, img VARCHAR(255) NOT NULL, gif VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE defi_exercice ADD CONSTRAINT FK_C88D081073F00F27 FOREIGN KEY (defi_id) REFERENCES defi (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE defi_exercice ADD CONSTRAINT FK_C88D081089D40298 FOREIGN KEY (exercice_id) REFERENCES exercice (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE defi_exercice DROP FOREIGN KEY FK_C88D081073F00F27');
        $this->addSql('ALTER TABLE defi_exercice DROP FOREIGN KEY FK_C88D081089D40298');
        $this->addSql('DROP TABLE defi');
        $this->addSql('DROP TABLE defi_exercice');
        $this->addSql('DROP TABLE exercice');
    }
}
