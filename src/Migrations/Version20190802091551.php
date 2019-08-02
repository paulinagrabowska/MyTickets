<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190802091551 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE venue (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, city VARCHAR(30) NOT NULL, street VARCHAR(45) NOT NULL, streetnumber INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE performers CHANGE stagename stagename VARCHAR(30) DEFAULT NULL, CHANGE image image VARCHAR(512) DEFAULT NULL');
        $this->addSql('ALTER TABLE concerts ADD venue_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE concerts ADD CONSTRAINT FK_730600F140A73EBA FOREIGN KEY (venue_id) REFERENCES venue (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_730600F140A73EBA ON concerts (venue_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE concerts DROP FOREIGN KEY FK_730600F140A73EBA');
        $this->addSql('DROP TABLE venue');
        $this->addSql('DROP INDEX UNIQ_730600F140A73EBA ON concerts');
        $this->addSql('ALTER TABLE concerts DROP venue_id');
        $this->addSql('ALTER TABLE performers CHANGE stagename stagename VARCHAR(30) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE image image VARCHAR(512) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
