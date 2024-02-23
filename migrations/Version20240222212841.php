<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240222212841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, id_client_id INT NOT NULL, INDEX IDX_C744045599DED506 (id_client_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C744045599DED506 FOREIGN KEY (id_client_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE coach DROP id_coach');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D43C105691');
        $this->addSql('DROP INDEX IDX_D044D5D43C105691 ON session');
        $this->addSql('ALTER TABLE session ADD vid VARCHAR(255) NOT NULL, ADD des VARCHAR(255) NOT NULL, CHANGE ID_Coach coach_id INT DEFAULT NULL, CHANGE capacity cap INT NOT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D43C105691 FOREIGN KEY (coach_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D43C105691 ON session (coach_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C744045599DED506');
        $this->addSql('DROP TABLE client');
        $this->addSql('ALTER TABLE coach ADD id_coach INT NOT NULL');
        $this->addSql('ALTER TABLE session DROP FOREIGN KEY FK_D044D5D43C105691');
        $this->addSql('DROP INDEX IDX_D044D5D43C105691 ON session');
        $this->addSql('ALTER TABLE session DROP vid, DROP des, CHANGE coach_id ID_Coach INT DEFAULT NULL, CHANGE cap capacity INT NOT NULL');
        $this->addSql('ALTER TABLE session ADD CONSTRAINT FK_D044D5D43C105691 FOREIGN KEY (ID_Coach) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D044D5D43C105691 ON session (ID_Coach)');
    }
}
