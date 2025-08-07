<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250807171231 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
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
        $this->addSql('ALTER TABLE persons ADD created_at DATETIME DEFAULT CURRENT_TIMESTAMP, ADD marital_status ENUM(\'single\', \'married\', \'widower\') DEFAULT \'single\', DROP address, CHANGE enable enable TINYINT(1) DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE persons RENAME INDEX uniq_a25cc7d3f85e0677 TO username');
        $this->addSql('ALTER TABLE persons RENAME INDEX uniq_a25cc7d3e7927c74 TO email');
    }
}
