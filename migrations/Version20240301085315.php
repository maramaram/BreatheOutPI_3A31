<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240301085315 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D687CB35');
        $this->addSql('DROP INDEX UNIQ_8D93D649D687CB35 ON user');
        $this->addSql('ALTER TABLE user CHANGE panierid_id panier_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F77D927C FOREIGN KEY (panier_id) REFERENCES panier (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649F77D927C ON user (panier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F77D927C');
        $this->addSql('DROP INDEX UNIQ_8D93D649F77D927C ON user');
        $this->addSql('ALTER TABLE user CHANGE panier_id panierid_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D687CB35 FOREIGN KEY (panierid_id) REFERENCES panier (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649D687CB35 ON user (panierid_id)');
    }
}
