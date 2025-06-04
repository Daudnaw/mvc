<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 * 
 * @SuppressWarnings(PHPMD)
 */
final class Version20250528195112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE acount (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, customer_id INTEGER NOT NULL, forname VARCHAR(255) NOT NULL, balance INTEGER DEFAULT NULL, CONSTRAINT FK_3191CE239395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_3191CE239395C3F3 ON acount (customer_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE customer (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, forname VARCHAR(255) NOT NULL, aftername VARCHAR(255) NOT NULL, adress VARCHAR(255) NOT NULL, telefon VARCHAR(255) NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE product
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL COLLATE "BINARY", value INTEGER NOT NULL)
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE acount
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE customer
        SQL);
    }
}
