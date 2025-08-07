<?php
namespace App\ex07\Controller;

use App\ex07\Entity\Ex07;
use App\ex07\Form\Ex07Type;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\TableNotFoundException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex07Controller extends AbstractController
{
    private function ensureTableExists(EntityManagerInterface $em): void
    {
        try {
            // Try to query the table to check if it exists
            $em->getConnection()->executeQuery('SELECT 1 FROM ex07 LIMIT 1');
        } catch (TableNotFoundException $e) {
            // Table doesn't exist, create it using Doctrine schema tools
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
            $metadata = $em->getMetadataFactory()->getMetadataFor(Ex07::class);
            $schemaTool->createSchema([$metadata]);
        } catch (\Exception $e) {
            // Table doesn't exist, create it using Doctrine schema tools
            $schemaTool = new \Doctrine\ORM\Tools\SchemaTool($em);
            $metadata = $em->getMetadataFactory()->getMetadataFor(Ex07::class);
            $schemaTool->createSchema([$metadata]);
        }
    }

    #[Route('/ex07', name: 'ex07_list')]
    public function list(Request $request, EntityManagerInterface $em): Response
    {
        $message = $request->query->get('message');
        $error = $request->query->get('error');
        
        try {
            $repository = $em->getRepository(Ex07::class);
            $users = $repository->findBy([], ['id' => 'DESC']);
        } catch (\Exception $e) {
            // Table doesn't exist yet, show empty list
            $users = [];
        }
        
        return $this->render('ex07/list.html.twig', [
            'users' => $users,
            'message' => $message,
            'error' => $error,
        ]);
    }

    #[Route('/ex07/add', name: 'ex07_add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        $user = new Ex07();
        $form = $this->createForm(Ex07Type::class, $user);
        $form->handleRequest($request);

        $message = null;
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Ensure table exists before trying to persist data
                $this->ensureTableExists($em);
                $em->persist($user);
                $em->flush();
                return $this->redirectToRoute('ex07_list', ['message' => 'User added successfully.']);
            } catch (\Exception $e) {
                $error = 'Failed to add user. Username or Email might already exist.';
            }
        }

        return $this->render('ex07/add.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
            'message' => $message,
        ]);
    }

    #[Route('/ex07/edit/{id}', name: 'ex07_edit')]
    public function edit(int $id, Request $request, EntityManagerInterface $em): Response
    {
        try {
            $repository = $em->getRepository(Ex07::class);
            $user = $repository->find($id);
        } catch (\Exception $e) {
            return $this->redirectToRoute('ex07_list', ['error' => 'Table does not exist yet.']);
        }

        if (!$user) {
            return $this->redirectToRoute('ex07_list', ['error' => 'User not found.']);
        }

        $form = $this->createForm(Ex07Type::class, $user);
        $form->handleRequest($request);

        $message = null;
        $error = null;

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $em->flush();
                return $this->redirectToRoute('ex07_list', ['message' => 'User updated successfully.']);
            } catch (\Exception $e) {
                $error = 'Update failed. Username or Email might already exist.';
            }
        }

        return $this->render('ex07/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
            'error' => $error,
            'message' => $message,
        ]);
    }
}
