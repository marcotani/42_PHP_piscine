<?php
namespace App\ex05\Controller;

use App\ex05\Entity\Ex05;
use App\ex05\Form\Ex05Type;
use App\ex05\Entity\Ex05Repository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex05Controller extends AbstractController
{
    #[Route('/ex05', name: 'ex05_list')]
    public function list(EntityManagerInterface $em, Request $request): Response
    {
        $message = $request->query->get('message');
        $error = $request->query->get('error');
        $users = $em->getRepository(Ex05::class)->findBy([], ['id' => 'DESC']);
        return $this->render('ex05/list.html.twig', [
            'users' => $users,
            'message' => $message,
            'error' => $error,
        ]);
    }

    #[Route('/ex05/delete/{id}', name: 'ex05_delete')]
    public function delete(int $id, EntityManagerInterface $em): Response
    {
        $user = $em->getRepository(Ex05::class)->find($id);
        if (!$user) {
            return $this->redirectToRoute('ex05_list', ['error' => 'User not found.']);
        }
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute('ex05_list', ['message' => 'User deleted successfully.']);
    }

    #[Route('/ex05/add', name: 'ex05_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        // Ensure table exists before adding
        $conn = $em->getConnection();
        try {
            $conn->fetchAllAssociative('SELECT 1 FROM ex05 LIMIT 1');
        } catch (\Doctrine\DBAL\Exception\TableNotFoundException $e) {
            $conn->executeStatement(<<<SQL
                CREATE TABLE IF NOT EXISTS ex05 (
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
        $user = new Ex05();
        $form = $this->createForm(Ex05Type::class, $user);
        $form->handleRequest($request);
        $error = null;
        $message = null;
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->persist($user);
                $em->flush();
                $message = 'User added successfully.';
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $error = 'Username or Email already exists.';
            }
        }
        return $this->render('ex05/add.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
            'message' => $message,
        ]);
    }
}
