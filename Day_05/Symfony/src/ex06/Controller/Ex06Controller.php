<?php
namespace App\ex06\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Connection;

class Ex06Controller extends AbstractController
{
    #[Route('/ex06', name: 'ex06_list')]
    public function list(Request $request, Connection $conn): Response
    {
        $message = $request->query->get('message');
        $error = $request->query->get('error');
        try {
            $users = $conn->fetchAllAssociative('SELECT * FROM ex06 ORDER BY id DESC');
        } catch (\Doctrine\DBAL\Exception\TableNotFoundException $e) {
            $users = [];
        }
        return $this->render('ex06/list.html.twig', [
            'users' => $users,
            'message' => $message,
            'error' => $error,
        ]);
    }

    #[Route('/ex06/edit/{id}', name: 'ex06_edit')]
    public function edit(int $id, Request $request, Connection $conn): Response
    {
        $user = $conn->fetchAssociative('SELECT * FROM ex06 WHERE id = ?', [$id]);
        if (!$user) {
            return $this->redirectToRoute('ex06_list', ['error' => 'User not found.']);
        }
        $error = null;
        $message = null;
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            try {
                $conn->executeStatement('UPDATE ex06 SET username = ?, name = ?, email = ?, enable = ?, birthdate = ?, address = ? WHERE id = ?', [
                    $data['username'],
                    $data['name'],
                    $data['email'],
                    isset($data['enable']) ? 1 : 0,
                    $data['birthdate'],
                    $data['address'],
                    $id
                ]);
                return $this->redirectToRoute('ex06_list', ['message' => 'User updated successfully.']);
            } catch (\Exception $e) {
                $error = 'Update failed.';
            }
        }
        return $this->render('ex06/edit.html.twig', [
            'user' => $user,
            'error' => $error,
            'message' => $message,
        ]);
    }

    #[Route('/ex06/add', name: 'ex06_add')]
    public function add(Request $request, Connection $conn): Response
    {
        // Try to create the table if it does not exist
        try {
            $conn->fetchAllAssociative('SELECT 1 FROM ex06 LIMIT 1');
        } catch (\Doctrine\DBAL\Exception\TableNotFoundException $e) {
            $conn->executeStatement(<<<SQL
                CREATE TABLE IF NOT EXISTS ex06 (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    username VARCHAR(255) NOT NULL UNIQUE,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    enable BOOLEAN NOT NULL,
                    birthdate DATETIME NOT NULL,
                    address LONGTEXT
                )
            SQL);
        }
        $error = null;
        $message = null;
        if ($request->isMethod('POST')) {
            $data = $request->request->all();
            try {
                $conn->insert('ex06', [
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
        return $this->render('ex06/add.html.twig', [
            'error' => $error,
            'message' => $message,
            'user' => $request->request->all(),
        ]);
    }
}