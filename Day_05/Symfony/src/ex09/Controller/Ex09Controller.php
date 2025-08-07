<?php
namespace App\ex09\Controller;

use App\ex09\Entity\Person;
use App\ex09\Entity\BankAccount;
use App\ex09\Entity\Address;
use App\ex09\Form\PersonType;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex09Controller extends AbstractController
{
    private function ensureTablesExist(EntityManagerInterface $em): void
    {
        try {
            // Try to query the tables to check if they exist
            $em->getConnection()->executeQuery('SELECT 1 FROM ex09_person LIMIT 1');
            $em->getConnection()->executeQuery('SELECT 1 FROM ex09_bank_account LIMIT 1');
            $em->getConnection()->executeQuery('SELECT 1 FROM ex09_address LIMIT 1');
        } catch (TableNotFoundException $e) {
            // Tables don't exist, create them using Doctrine schema tools
            $this->createSchema($em);
        } catch (\Exception $e) {
            // Tables don't exist, create them using Doctrine schema tools
            $this->createSchema($em);
        }
    }
    
    private function createSchema(EntityManagerInterface $em): void
    {
        try {
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
            $classes = [
                $em->getMetadataFactory()->getMetadataFor(Person::class),
                $em->getMetadataFactory()->getMetadataFor(BankAccount::class),
                $em->getMetadataFactory()->getMetadataFor(Address::class),
            ];
            $schemaTool->createSchema($classes);
        } catch (\Exception $e) {
            // If schema tool fails, try manual creation
            $this->createTablesManually($em);
        }
    }
    
    private function createTablesManually(EntityManagerInterface $em): void
    {
        $conn = $em->getConnection();
        
        try {
            // Create Person table
            $personSql = <<<SQL
                CREATE TABLE IF NOT EXISTS ex09_person (
                    id INT AUTO_INCREMENT NOT NULL,
                    username VARCHAR(255) NOT NULL,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL,
                    enable TINYINT(1) NOT NULL,
                    birthdate DATETIME NOT NULL,
                    created_at DATETIME NOT NULL,
                    marital_status VARCHAR(20) DEFAULT NULL,
                    UNIQUE INDEX UNIQ_4BAA2FF5F85E0677 (username),
                    UNIQUE INDEX UNIQ_4BAA2FF5E7927C74 (email),
                    PRIMARY KEY (id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            SQL;
            
            // Create BankAccount table
            $bankAccountSql = <<<SQL
                CREATE TABLE IF NOT EXISTS ex09_bank_account (
                    id INT AUTO_INCREMENT NOT NULL,
                    account_number VARCHAR(20) NOT NULL,
                    bank_name VARCHAR(255) NOT NULL,
                    balance NUMERIC(10, 2) NOT NULL,
                    created_at DATETIME NOT NULL,
                    person_id INT NOT NULL,
                    UNIQUE INDEX UNIQ_2D7B666DB1A4D127 (account_number),
                    UNIQUE INDEX UNIQ_2D7B666D217BBB47 (person_id),
                    PRIMARY KEY (id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            SQL;
            
            // Create Address table
            $addressSql = <<<SQL
                CREATE TABLE IF NOT EXISTS ex09_address (
                    id INT AUTO_INCREMENT NOT NULL,
                    type VARCHAR(20) NOT NULL,
                    street VARCHAR(255) NOT NULL,
                    city VARCHAR(100) NOT NULL,
                    state VARCHAR(100) DEFAULT NULL,
                    postal_code VARCHAR(20) DEFAULT NULL,
                    country VARCHAR(100) NOT NULL,
                    created_at DATETIME NOT NULL,
                    person_id INT NOT NULL,
                    INDEX IDX_7980CBE5217BBB47 (person_id),
                    PRIMARY KEY (id)
                ) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci
            SQL;
            
            // Create foreign key constraints
            $fkSql1 = 'ALTER TABLE ex09_address ADD CONSTRAINT FK_7980CBE5217BBB47 FOREIGN KEY (person_id) REFERENCES ex09_person (id)';
            $fkSql2 = 'ALTER TABLE ex09_bank_account ADD CONSTRAINT FK_2D7B666D217BBB47 FOREIGN KEY (person_id) REFERENCES ex09_person (id)';
            
            $conn->executeStatement($personSql);
            $conn->executeStatement($bankAccountSql);
            $conn->executeStatement($addressSql);
            
            // Try to add foreign keys, ignore if they already exist
            try {
                $conn->executeStatement($fkSql1);
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
            
            try {
                $conn->executeStatement($fkSql2);
            } catch (\Exception $e) {
                // Foreign key might already exist
            }
            
        } catch (\Exception $e) {
            // Continue silently if tables already exist or other issues
        }
    }

    #[Route('/ex09', name: 'ex09_home')]
    public function home(Request $request, EntityManagerInterface $em): Response
    {
        $message = $request->query->get('message');
        $error = $request->query->get('error');
        
        // Get all persons with their relationships
        try {
            $repository = $em->getRepository(Person::class);
            $persons = $repository->findAllWithRelations();
        } catch (\Exception $e) {
            // Tables don't exist yet, show empty list
            $persons = [];
            if (!$error) {
                $error = 'Tables do not exist yet. Please create them first using the buttons below.';
            }
        }
        
        return $this->render('ex09/home.html.twig', [
            'message' => $message,
            'error' => $error,
            'persons' => $persons,
        ]);
    }

    #[Route('/ex09/create-schema', name: 'ex09_create_schema')]
    public function createSchemaAction(EntityManagerInterface $em): Response
    {
        try {
            $this->createSchema($em);
            return $this->redirectToRoute('ex09_home', ['message' => 'Database schema created successfully!']);
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Failed to create schema: ' . $e->getMessage()]);
        }
    }

    #[Route('/ex09/create-person', name: 'ex09_create_person')]
    public function createPerson(Request $request, EntityManagerInterface $em): Response
    {
        $person = new Person();
        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Ensure tables exist before trying to persist data
                $this->ensureTablesExist($em);
                $em->persist($person);
                $em->flush();
                return $this->redirectToRoute('ex09_home', ['message' => 'Person created successfully!']);
            } catch (\Exception $e) {
                return $this->redirectToRoute('ex09_create_person', ['error' => 'Failed to create person: ' . $e->getMessage()]);
            }
        }

        return $this->render('ex09/create_person.html.twig', [
            'form' => $form->createView(),
            'error' => $request->query->get('error'),
        ]);
    }

    #[Route('/ex09/edit-person/{id}', name: 'ex09_edit_person')]
    public function editPerson(int $id, Request $request, EntityManagerInterface $em): Response
    {
        try {
            $person = $em->getRepository(Person::class)->find($id);
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Tables do not exist yet.']);
        }
        
        if (!$person) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Person not found.']);
        }

        $form = $this->createForm(PersonType::class, $person);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();
                return $this->redirectToRoute('ex09_home', ['message' => 'Person updated successfully!']);
            } catch (\Exception $e) {
                return $this->redirectToRoute('ex09_edit_person', ['id' => $id, 'error' => 'Failed to update person: ' . $e->getMessage()]);
            }
        }

        return $this->render('ex09/edit_person.html.twig', [
            'form' => $form->createView(),
            'person' => $person,
            'error' => $request->query->get('error'),
        ]);
    }

    #[Route('/ex09/add-marital-status', name: 'ex09_add_marital_status')]
    public function addMaritalStatusColumn(EntityManagerInterface $em): Response
    {
        // This demonstrates the migration that adds the marital_status column
        // In a real scenario, this would be done via Doctrine migrations
        // Here we just show that the column exists and can be used
        
        try {
            // Check if we can use the marital_status field
            $connection = $em->getConnection();
            $result = $connection->fetchOne("SHOW COLUMNS FROM ex09_person LIKE 'marital_status'");
            
            if ($result) {
                return $this->redirectToRoute('ex09_home', ['message' => 'Marital status column already exists and is ready to use!']);
            } else {
                return $this->redirectToRoute('ex09_home', ['error' => 'Marital status column does not exist. Please run migrations.']);
            }
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Error checking marital status column: ' . $e->getMessage()]);
        }
    }

    #[Route('/ex09/manage-bank-account/{id}', name: 'ex09_manage_bank_account')]
    public function manageBankAccount(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Ensure tables exist before trying to query them
        $this->ensureTablesExist($em);
        
        $person = $em->getRepository(Person::class)->find($id);
        if (!$person) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Person not found.']);
        }

        // Get or create bank account
        $bankAccount = $person->getBankAccount();
        if (!$bankAccount) {
            $bankAccount = new BankAccount();
            $person->setBankAccount($bankAccount);
        }

        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            try {
                $bankAccount->setAccountNumber($data['account_number']);
                $bankAccount->setBankName($data['bank_name']);
                $bankAccount->setBalance($data['balance'] ?? '0.00');
                
                $em->persist($bankAccount);
                $em->flush();
                
                return $this->redirectToRoute('ex09_home', ['message' => 'Bank account updated successfully!']);
            } catch (\Exception $e) {
                return $this->redirectToRoute('ex09_manage_bank_account', ['id' => $id, 'error' => 'Failed to update bank account: ' . $e->getMessage()]);
            }
        }

        return $this->render('ex09/manage_bank_account.html.twig', [
            'person' => $person,
            'bankAccount' => $bankAccount,
            'error' => $request->query->get('error'),
        ]);
    }

    #[Route('/ex09/manage-addresses/{id}', name: 'ex09_manage_addresses')]
    public function manageAddresses(int $id, Request $request, EntityManagerInterface $em): Response
    {
        // Ensure tables exist before trying to query them
        $this->ensureTablesExist($em);
        
        $person = $em->getRepository(Person::class)->find($id);
        if (!$person) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Person not found.']);
        }

        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            try {
                $address = new Address();
                $address->setType($data['type']);
                $address->setStreet($data['street']);
                $address->setCity($data['city']);
                $address->setState($data['state'] ?? null);
                $address->setPostalCode($data['postal_code'] ?? null);
                $address->setCountry($data['country']);
                
                $person->addAddress($address);
                $em->persist($address);
                $em->flush();
                
                return $this->redirectToRoute('ex09_manage_addresses', ['id' => $id, 'message' => 'Address added successfully!']);
            } catch (\Exception $e) {
                return $this->redirectToRoute('ex09_manage_addresses', ['id' => $id, 'error' => 'Failed to add address: ' . $e->getMessage()]);
            }
        }

        return $this->render('ex09/manage_addresses.html.twig', [
            'person' => $person,
            'addresses' => $person->getAddresses(),
            'message' => $request->query->get('message'),
            'error' => $request->query->get('error'),
        ]);
    }

    #[Route('/ex09/delete-address/{id}', name: 'ex09_delete_address')]
    public function deleteAddress(int $id, EntityManagerInterface $em): Response
    {
        $address = $em->getRepository(Address::class)->find($id);
        if (!$address) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Address not found.']);
        }

        $personId = $address->getPerson()->getId();
        
        try {
            $em->remove($address);
            $em->flush();
            return $this->redirectToRoute('ex09_manage_addresses', ['id' => $personId, 'message' => 'Address deleted successfully!']);
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex09_manage_addresses', ['id' => $personId, 'error' => 'Failed to delete address.']);
        }
    }

    #[Route('/ex09/schema-info', name: 'ex09_schema_info')]
    public function schemaInfo(EntityManagerInterface $em): Response
    {
        // Ensure tables exist before trying to query them
        $this->ensureTablesExist($em);
        
        try {
            $connection = $em->getConnection();
            
            $tables = [];
            foreach (['ex09_person', 'ex09_bank_account', 'ex09_address'] as $tableName) {
                try {
                    $columns = $connection->fetchAllAssociative("DESCRIBE {$tableName}");
                    $tables[$tableName] = $columns;
                } catch (\Exception $e) {
                    $tables[$tableName] = [];
                }
            }
            
            return $this->render('ex09/schema_info.html.twig', [
                'tables' => $tables,
            ]);
            
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex09_home', ['error' => 'Error retrieving schema info: ' . $e->getMessage()]);
        }
    }
}
