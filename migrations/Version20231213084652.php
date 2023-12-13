<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231213084652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, title, location, begin_date, end_date, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, author_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, begin_date DATETIME NOT NULL, end_date DATETIME NOT NULL, description CLOB NOT NULL, CONSTRAINT FK_3BAE0AA7F675F31B FOREIGN KEY (author_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO event (id, title, location, begin_date, end_date, description) SELECT id, title, location, begin_date, end_date, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
        $this->addSql('CREATE INDEX IDX_3BAE0AA7F675F31B ON event (author_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, title, location, begin_date, end_date, description FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, begin_date DATETIME NOT NULL, end_date DATETIME NOT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO event (id, title, location, begin_date, end_date, description) SELECT id, title, location, begin_date, end_date, description FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
    }
}
