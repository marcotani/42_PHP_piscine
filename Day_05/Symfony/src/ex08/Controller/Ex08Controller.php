<?php
namespace App\ex08\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class Ex08Controller extends AbstractController
{
    private function ensureTablesExist(Connection $conn): void
    {
        try {
            // Create persons table if it doesn't exist
            $sql = <<<SQL
                CREATE TABLE IF NOT EXISTS persons (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) NOT NULL UNIQUE,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    enable BOOLEAN NOT NULL DEFAULT 0,
                    birthdate DATETIME NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            SQL;
            $conn->executeStatement($sql);
            
            // Check if marital_status column exists, if not add it
            $columnExists = $conn->fetchOne(
                "SELECT COUNT(*) FROM information_schema.COLUMNS 
                 WHERE TABLE_SCHEMA = DATABASE() 
                 AND TABLE_NAME = 'persons' 
                 AND COLUMN_NAME = 'marital_status'"
            );

            if ($columnExists == 0) {
                $conn->executeStatement("ALTER TABLE persons ADD COLUMN marital_status VARCHAR(20) DEFAULT NULL");
            }
            
            // Create bank_accounts table if it doesn't exist
            $bankAccountSql = <<<SQL
                CREATE TABLE IF NOT EXISTS bank_accounts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    person_id INT NOT NULL,
                    account_number VARCHAR(20) NOT NULL UNIQUE,
                    bank_name VARCHAR(255) NOT NULL,
                    balance DECIMAL(10,2) NOT NULL DEFAULT 0.00,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE,
                    UNIQUE KEY unique_person_account (person_id)
                )
            SQL;
            $conn->executeStatement($bankAccountSql);
            
            // Create addresses table if it doesn't exist
            $addressSql = <<<SQL
                CREATE TABLE IF NOT EXISTS addresses (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    person_id INT NOT NULL,
                    type VARCHAR(20) NOT NULL,
                    street VARCHAR(255) NOT NULL,
                    city VARCHAR(100) NOT NULL,
                    state VARCHAR(100),
                    postal_code VARCHAR(20),
                    country VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (person_id) REFERENCES persons(id) ON DELETE CASCADE
                )
            SQL;
            $conn->executeStatement($addressSql);
            
        } catch (\Exception $e) {
            // Tables might already exist or have issues, continue silently
            // In a production environment, you'd want to log this
        }
    }

    #[Route('/ex08', name: 'ex08_home')]
    public function home(Request $request): Response
    {
        $message = $request->query->get('message');
        $error = $request->query->get('error');
        
        return $this->render('ex08/home.html.twig', [
            'message' => $message,
            'error' => $error,
        ]);
    }

    #[Route('/ex08/create-persons-table', name: 'ex08_create_persons')]
    public function createPersonsTable(Connection $conn): Response
    {
        try {
            $sql = <<<SQL
                CREATE TABLE IF NOT EXISTS persons (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) NOT NULL UNIQUE,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    enable BOOLEAN NOT NULL DEFAULT 0,
                    birthdate DATETIME NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            SQL;
            
            $conn->executeStatement($sql);
            return $this->redirectToRoute('ex08_home', ['message' => 'Persons table created successfully!']);
            
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex08_home', ['error' => 'Error creating persons table: ' . $e->getMessage()]);
        }
    }

    #[Route('/ex08/alter-persons-table', name: 'ex08_alter_persons')]
    public function alterPersonsTable(Connection $conn): Response
    {
        try {
            // Check if column already exists
            $columnExists = $conn->fetchOne(
                "SELECT COUNT(*) FROM information_schema.COLUMNS 
                 WHERE TABLE_SCHEMA = DATABASE() 
                 AND TABLE_NAME = 'persons' 
                 AND COLUMN_NAME = 'marital_status'"
            );

            if ($columnExists > 0) {
                return $this->redirectToRoute('ex08_home', ['message' => 'Marital status column already exists!']);
            }

            $sql = <<<SQL
                ALTER TABLE persons 
                ADD COLUMN marital_status ENUM('single', 'married', 'widower') DEFAULT 'single'
            SQL;
            
            $conn->executeStatement($sql);
            return $this->redirectToRoute('ex08_home', ['message' => 'Marital status column added successfully!']);
            
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex08_home', ['error' => 'Error altering persons table: ' . $e->getMessage()]);
        }
    }

    #[Route('/ex08/create-related-tables', name: 'ex08_create_related_tables')]
    public function createRelatedTables(Connection $conn): Response
    {
        try {
            // Create bank_accounts table (one-to-one with persons)
            $sqlBankAccounts = <<<SQL
                CREATE TABLE IF NOT EXISTS bank_accounts (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    person_id INT NOT NULL UNIQUE,
                    account_number VARCHAR(20) NOT NULL UNIQUE,
                    bank_name VARCHAR(255) NOT NULL,
                    balance DECIMAL(10,2) DEFAULT 0.00,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_person_id (person_id)
                )
            SQL;
            
            // Create addresses table (one-to-many with persons)
            $sqlAddresses = <<<SQL
                CREATE TABLE IF NOT EXISTS addresses (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    person_id INT NOT NULL,
                    type ENUM('home', 'work', 'billing', 'shipping') NOT NULL,
                    street VARCHAR(255) NOT NULL,
                    city VARCHAR(100) NOT NULL,
                    state VARCHAR(100),
                    postal_code VARCHAR(20),
                    country VARCHAR(100) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    INDEX idx_person_id (person_id)
                )
            SQL;
            
            $conn->executeStatement($sqlBankAccounts);
            $conn->executeStatement($sqlAddresses);
            
            return $this->redirectToRoute('ex08_home', ['message' => 'Bank accounts and addresses tables created successfully!']);
            
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex08_home', ['error' => 'Error creating related tables: ' . $e->getMessage()]);
        }
    }

    #[Route('/ex08/create-relationships', name: 'ex08_create_relationships')]
    public function createRelationships(Connection $conn): Response
    {
        try {
            // Check if persons table exists
            $personsExists = $conn->fetchOne(
                "SELECT COUNT(*) FROM information_schema.tables 
                 WHERE table_schema = DATABASE() 
                 AND table_name = 'persons'"
            );

            if ($personsExists == 0) {
                return $this->redirectToRoute('ex08_home', ['error' => 'Persons table must be created first!']);
            }

            // Create foreign key constraint for bank_accounts (one-to-one)
            $sqlBankAccountsFK = <<<SQL
                ALTER TABLE bank_accounts 
                ADD CONSTRAINT fk_bank_accounts_person 
                FOREIGN KEY (person_id) REFERENCES persons(id) 
                ON DELETE CASCADE ON UPDATE CASCADE
            SQL;
            
            // Create foreign key constraint for addresses (one-to-many)
            $sqlAddressesFK = <<<SQL
                ALTER TABLE addresses 
                ADD CONSTRAINT fk_addresses_person 
                FOREIGN KEY (person_id) REFERENCES persons(id) 
                ON DELETE CASCADE ON UPDATE CASCADE
            SQL;
            
            // Try to add foreign keys, ignore if they already exist
            try {
                $conn->executeStatement($sqlBankAccountsFK);
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
            
            try {
                $conn->executeStatement($sqlAddressesFK);
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
            
            return $this->redirectToRoute('ex08_home', ['message' => 'Foreign key relationships created successfully!']);
            
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex08_home', ['error' => 'Error creating relationships: ' . $e->getMessage()]);
        }
    }

    #[Route('/ex08/show-tables', name: 'ex08_show_tables')]
    public function showTables(Connection $conn): Response
    {
        try {
            $tables = [];
            
            // Get all tables in current database
            $tableNames = $conn->fetchFirstColumn("SHOW TABLES");
            
            foreach ($tableNames as $tableName) {
                if (in_array($tableName, ['persons', 'bank_accounts', 'addresses'])) {
                    $structure = $conn->fetchAllAssociative("DESCRIBE {$tableName}");
                    $tables[$tableName] = $structure;
                }
            }
            
            return $this->render('ex08/tables.html.twig', [
                'tables' => $tables,
            ]);
            
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex08_home', ['error' => 'Error showing tables: ' . $e->getMessage()]);
        }
    }

}
