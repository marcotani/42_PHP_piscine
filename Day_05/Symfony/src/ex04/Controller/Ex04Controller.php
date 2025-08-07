<?php
namespace App\ex04\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class Ex04Controller extends AbstractController
{
    #[Route('/ex04', name: 'ex04_list')]
    public function list(Request $request, Connection $conn): Response
    {
        $conn->executeStatement(<<<SQL
            CREATE TABLE IF NOT EXISTS ex04 (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                enable BOOLEAN NOT NULL,
                birthdate DATETIME NOT NULL,
                address LONGTEXT
            )
        SQL);
        $message = $request->query->get('message');
        $error = $request->query->get('error');
        $users = $conn->fetchAllAssociative('SELECT * FROM ex04 ORDER BY id DESC');
        return $this->render('ex04/list.html.twig', [
            'users' => $users,
            'message' => $message,
            'error' => $error,
        ]);
    }

    #[Route('/ex04/delete/{id}', name: 'ex04_delete')]
    public function delete(int $id, Connection $conn): Response
    {
        $user = $conn->fetchAssociative('SELECT * FROM ex04 WHERE id = ?', [$id]);
        if (!$user) {
            return $this->redirectToRoute('ex04_list', ['error' => 'User not found.']);
        }
        $conn->executeStatement('DELETE FROM ex04 WHERE id = ?', [$id]);
        return $this->redirectToRoute('ex04_list', ['message' => 'User deleted successfully.']);
    }

    #[Route('/ex04/add', name: 'ex04_add')]
    public function add(Request $request, Connection $conn): Response
    {
        $conn->executeStatement(<<<SQL
            CREATE TABLE IF NOT EXISTS ex04 (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                enable BOOLEAN NOT NULL,
                birthdate DATETIME NOT NULL,
                address LONGTEXT
            )
        SQL);
        $error = null;
        $message = null;
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            try {
                $conn->insert('ex04', [
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'enable' => isset($data['enable']) ? 1 : 0,
                    'birthdate' => $data['birthdate'],
                    'address' => $data['address'],
                ]);
                $message = 'User added successfully.';
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $error = 'Username or Email already exists.';
            }
        }
        return $this->render('ex04/add.html.twig', [
            'error' => $error,
            'message' => $message,
        ]);
    }
}
