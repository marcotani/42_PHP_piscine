<?php
namespace App\ex14\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex14Controller extends AbstractController
{
    #[Route('/ex14', name: 'ex14_home')]
    public function index(Connection $conn, Request $request): Response
    {
        // Create table if not exists
        $tableStatus = '';
        try {
            $conn->executeStatement("CREATE TABLE IF NOT EXISTS ex14_vuln (id INT AUTO_INCREMENT PRIMARY KEY, content VARCHAR(255))");
            $tableStatus = 'Table exists';
        } catch (\Exception $e) {
            $tableStatus = 'Table does not exist: ' . $e->getMessage();
        }
        // Get all rows
        $rows = $conn->fetchAllAssociative('SELECT * FROM ex14_vuln');
        $message = $request->query->get('message', '');
        return $this->render('ex14/home.html.twig', [
            'tableStatus' => $tableStatus,
            'rows' => $rows,
            'message' => $message,
        ]);
    }

    #[Route('/ex14/insert', name: 'ex14_insert', methods: ['POST'])]
    public function insert(Connection $conn, Request $request): Response
    {
        $content = $request->request->get('content', '');
        // Intentionally vulnerable: direct SQL injection
        $sql = "INSERT INTO ex14_vuln (content) VALUES ('$content')";
        try {
            $conn->executeStatement($sql);
            $msg = 'Inserted!';
        } catch (\Exception $e) {
            $msg = 'Error: ' . $e->getMessage();
        }
        return $this->redirectToRoute('ex14_home', ['message' => $msg]);
    }
}
