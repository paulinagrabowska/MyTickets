<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190803104054 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE concerts (id INT AUTO_INCREMENT NOT NULL, performer_id INT NOT NULL, venue_id INT NOT NULL, name VARCHAR(45) NOT NULL, info VARCHAR(512) NOT NULL, date DATETIME NOT NULL, reservation_limit INT NOT NULL, INDEX IDX_730600F16C6B33F3 (performer_id), INDEX IDX_730600F140A73EBA (venue_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE concerts_tags (concert_id INT NOT NULL, tag_id INT NOT NULL, INDEX IDX_4752CB5783C97B2E (concert_id), INDEX IDX_4752CB57BAD26311 (tag_id), PRIMARY KEY(concert_id, tag_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE performers (id INT AUTO_INCREMENT NOT NULL, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, stagename VARCHAR(30) DEFAULT NULL, info VARCHAR(512) NOT NULL, musicgenre VARCHAR(30) NOT NULL, code VARCHAR(30) NOT NULL, image VARCHAR(512) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(30) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE venues (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(45) NOT NULL, city VARCHAR(30) NOT NULL, street VARCHAR(45) NOT NULL, streetnumber INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE concerts ADD CONSTRAINT FK_730600F16C6B33F3 FOREIGN KEY (performer_id) REFERENCES performers (id)');
        $this->addSql('ALTER TABLE concerts ADD CONSTRAINT FK_730600F140A73EBA FOREIGN KEY (venue_id) REFERENCES venues (id)');
        $this->addSql('ALTER TABLE concerts_tags ADD CONSTRAINT FK_4752CB5783C97B2E FOREIGN KEY (concert_id) REFERENCES concerts (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE concerts_tags ADD CONSTRAINT FK_4752CB57BAD26311 FOREIGN KEY (tag_id) REFERENCES tags (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE concerts_tags DROP FOREIGN KEY FK_4752CB5783C97B2E');
        $this->addSql('ALTER TABLE concerts DROP FOREIGN KEY FK_730600F16C6B33F3');
        $this->addSql('ALTER TABLE concerts_tags DROP FOREIGN KEY FK_4752CB57BAD26311');
        $this->addSql('ALTER TABLE concerts DROP FOREIGN KEY FK_730600F140A73EBA');
        $this->addSql('DROP TABLE concerts');
        $this->addSql('DROP TABLE concerts_tags');
        $this->addSql('DROP TABLE performers');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE venues');
    }
}
