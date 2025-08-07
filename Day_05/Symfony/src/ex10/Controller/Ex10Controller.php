<?php
namespace App\ex10\Controller;

use App\Entity\Ex10Item;
use Doctrine\DBAL\Connection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex10Controller extends AbstractController
{
    #[Route('/ex10', name: 'ex10_home')]
    public function index(Connection $conn, EntityManagerInterface $em): Response
    {
        // Ensure SQL table exists
        $conn->executeStatement(<<<SQL
            CREATE TABLE IF NOT EXISTS ex10_sql (
                id INT AUTO_INCREMENT PRIMARY KEY,
                content VARCHAR(255) NOT NULL
            )
        SQL);

        // Get all items from SQL table
        $sqlItems = $conn->fetchAllAssociative('SELECT * FROM ex10_sql');
        // Get all items from ORM entity
        $ormItems = $em->getRepository(Ex10Item::class)->findAll();

        return $this->render('ex10/home.html.twig', [
            'sqlItems' => $sqlItems,
            'ormItems' => $ormItems,
        ]);
    }

    #[Route('/ex10/import', name: 'ex10_import')]
    public function import(Connection $conn, EntityManagerInterface $em): Response
    {
    $file = $this->getParameter('kernel.project_dir') . '/ex10.txt';
        $message = '';
        $error = '';
        if (!file_exists($file) || !is_readable($file)) {
            $error = 'File not found or not readable: ' . $file;
        } else {
            $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                // Insert into SQL table
                $conn->insert('ex10_sql', ['content' => $line]);
                // Insert into ORM entity
                $item = new Ex10Item();
                $item->setContent($line);
                $em->persist($item);
            }
            $em->flush();
            $message = 'Imported ' . count($lines) . ' lines from file.';
        }
        return $this->redirectToRoute('ex10_home', [
            'message' => $message,
            'error' => $error,
        ]);
    }
}
