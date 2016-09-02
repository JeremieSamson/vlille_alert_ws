<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
class Version20160902135806 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE attachs_available (id INT AUTO_INCREMENT NOT NULL, station_id INT DEFAULT NULL, attachs INT NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_5A37B68421BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bikes_available (id INT AUTO_INCREMENT NOT NULL, station_id INT DEFAULT NULL, bikes INT NOT NULL, createdAt DATETIME NOT NULL, INDEX IDX_2A72A00521BDB235 (station_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attachs_available ADD CONSTRAINT FK_5A37B68421BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE bikes_available ADD CONSTRAINT FK_2A72A00521BDB235 FOREIGN KEY (station_id) REFERENCES station (id)');
        $this->addSql('ALTER TABLE station DROP bikes, DROP attachs');
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE attachs_available');
        $this->addSql('DROP TABLE bikes_available');
        $this->addSql('ALTER TABLE station ADD bikes INT DEFAULT NULL, ADD attachs INT DEFAULT NULL');
    }
}
