<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807171308 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ex09_address (id INT AUTO_INCREMENT NOT NULL, type VARCHAR(20) NOT NULL, street VARCHAR(255) NOT NULL, city VARCHAR(100) NOT NULL, state VARCHAR(100) DEFAULT NULL, postal_code VARCHAR(20) DEFAULT NULL, country VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, person_id INT NOT NULL, INDEX IDX_7980CBE5217BBB47 (person_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ex09_bank_account (id INT AUTO_INCREMENT NOT NULL, account_number VARCHAR(20) NOT NULL, bank_name VARCHAR(255) NOT NULL, balance NUMERIC(10, 2) NOT NULL, created_at DATETIME NOT NULL, person_id INT NOT NULL, UNIQUE INDEX UNIQ_2D7B666DB1A4D127 (account_number), UNIQUE INDEX UNIQ_2D7B666D217BBB47 (person_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE ex09_person (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, created_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_4BAA2FF5F85E0677 (username), UNIQUE INDEX UNIQ_4BAA2FF5E7927C74 (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE ex09_address ADD CONSTRAINT FK_7980CBE5217BBB47 FOREIGN KEY (person_id) REFERENCES ex09_person (id)');
        $this->addSql('ALTER TABLE ex09_bank_account ADD CONSTRAINT FK_2D7B666D217BBB47 FOREIGN KEY (person_id) REFERENCES ex09_person (id)');
        $this->addSql('ALTER TABLE addresses DROP FOREIGN KEY `fk_addresses_person`');
        $this->addSql('ALTER TABLE bank_accounts DROP FOREIGN KEY `fk_bank_accounts_person`');
        $this->addSql('DROP TABLE addresses');
        $this->addSql('DROP TABLE bank_accounts');
        $this->addSql('DROP TABLE ex06');
        $this->addSql('ALTER TABLE persons ADD address LONGTEXT NOT NULL, DROP created_at, DROP marital_status, CHANGE enable enable TINYINT(1) NOT NULL');
        $this->addSql('ALTER TABLE persons RENAME INDEX username TO UNIQ_A25CC7D3F85E0677');
        $this->addSql('ALTER TABLE persons RENAME INDEX email TO UNIQ_A25CC7D3E7927C74');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE addresses (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, type ENUM(\'home\', \'work\', \'billing\', \'shipping\') CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, street VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, city VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, state VARCHAR(100) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, postal_code VARCHAR(20) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, country VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, created_at DATETIME DEFAULT CURRENT_TIMESTAMP, INDEX idx_person_id (person_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE bank_accounts (id INT AUTO_INCREMENT NOT NULL, person_id INT NOT NULL, account_number VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, bank_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, balance NUMERIC(10, 2) DEFAULT \'0.00\', created_at DATETIME DEFAULT CURRENT_TIMESTAMP, UNIQUE INDEX person_id (person_id), UNIQUE INDEX account_number (account_number), INDEX idx_person_id (person_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ex06 (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, enable TINYINT(1) NOT NULL, birthdate DATETIME NOT NULL, address LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_general_ci`, UNIQUE INDEX username (username), UNIQUE INDEX email (email), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE addresses ADD CONSTRAINT `fk_addresses_person` FOREIGN KEY (person_id) REFERENCES persons (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE bank_accounts ADD CONSTRAINT `fk_bank_accounts_person` FOREIGN KEY (person_id) REFERENCES persons (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ex09_address DROP FOREIGN KEY FK_7980CBE5217BBB47');
        $this->addSql('ALTER TABLE ex09_bank_account DROP FOREIGN KEY FK_2D7B666D217BBB47');
        $this->addSql('DROP TABLE ex09_address');
        $this->addSql('DROP TABLE ex09_bank_account');
        $this->addSql('DROP TABLE ex09_person');
        $this->addSql('ALTER TABLE persons ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD marital_status ENUM(\'single\', \'married\', \'widower\') DEFAULT \'single\', DROP address, CHANGE enable enable TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE persons RENAME INDEX uniq_a25cc7d3f85e0677 TO username');
        $this->addSql('ALTER TABLE persons RENAME INDEX uniq_a25cc7d3e7927c74 TO email');
    }
}
