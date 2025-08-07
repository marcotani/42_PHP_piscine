<?php
namespace App\ex02\Controller;

use App\ex02\Form\UserType;
use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Route('/ex02', name: 'ex02_form')]
    public function index(Request $request, Connection $conn): Response
    {
        $conn->executeStatement("
            CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                username VARCHAR(255) NOT NULL UNIQUE,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                enable BOOLEAN NOT NULL,
                birthdate DATETIME NOT NULL,
                address LONGTEXT
            )
        ");

        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            try {
                $conn->insert('users', [
                    'username' => $data['username'],
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'enable' => (int) ($data['enable'] ?? 0),
                    'birthdate' => $data['birthdate']->format('Y-m-d H:i:s'),
                    'address' => $data['address'],
                ]);
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $this->addFlash('error', 'Username or Email already exists.');
            }

            return $this->redirectToRoute('ex02_form');
        }

        return $this->render('ex02/form.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/ex02/list', name: 'ex02_list')]
    public function list(Connection $conn): Response
    {
        $users = $conn->fetchAllAssociative('SELECT * FROM users ORDER BY id DESC');

        return $this->render('ex02/list.html.twig', [
            'users' => $users,
        ]);
    }
}

