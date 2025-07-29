<?php

namespace App\ex00\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    #[Route('/ex00', name: 'ex00_index', methods: ['GET'])]
    public function index(): Response
    {
        // Just show the page with the button, no SQL executed here
        return $this->render('ex00/index.html.twig');
    }

    #[Route('/ex00/create-table', name: 'ex00_create_table', methods: ['POST'])]
    public function createTable(Connection $connection): Response
    {
        $sql = <<<SQL
        CREATE TABLE IF NOT EXISTS persons (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(255) UNIQUE NOT NULL,
            name VARCHAR(255) NOT NULL,
            email VARCHAR(255) UNIQUE NOT NULL,
            enable BOOLEAN NOT NULL,
            birthdate DATETIME NOT NULL,
            address LONGTEXT
        );
        SQL;

        try {
            $connection->executeStatement($sql);
            $message = "✅ Table 'persons' created successfully (or already exists).";
        } catch (\Exception $e) {
            $message = "❌ Failed to create table: " . $e->getMessage();
        }

        // Render the same page, passing the message to display feedback
        return $this->render('ex00/index.html.twig', [
            'message' => $message
        ]);
    }
}
