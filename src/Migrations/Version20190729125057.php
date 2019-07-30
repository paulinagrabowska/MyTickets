<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190729125057 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE concerts ADD performer_id INT NOT NULL');
        $this->addSql('ALTER TABLE concerts ADD CONSTRAINT FK_730600F16C6B33F3 FOREIGN KEY (performer_id) REFERENCES performers (id)');
        $this->addSql('CREATE INDEX IDX_730600F16C6B33F3 ON concerts (performer_id)');
        $this->addSql('ALTER TABLE performers CHANGE stagename stagename VARCHAR(30) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE concerts DROP FOREIGN KEY FK_730600F16C6B33F3');
        $this->addSql('DROP INDEX IDX_730600F16C6B33F3 ON concerts');
        $this->addSql('ALTER TABLE concerts DROP performer_id');
        $this->addSql('ALTER TABLE performers CHANGE stagename stagename VARCHAR(30) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
    }
}
