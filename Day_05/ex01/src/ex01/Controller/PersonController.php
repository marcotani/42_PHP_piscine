<?php

namespace App\ex01\Controller;

use App\ex01\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\SchemaTool;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PersonController extends AbstractController
{
    #[Route('/ex01', name: 'ex01_home')]
    public function index(): Response
    {
        // Use bundle-style template path
        return $this->render('ex01/index.html.twig');
    }

    #[Route('/ex01/create-table', name: 'ex01_create_table', methods: ['POST', 'GET'])]
    public function createTable(EntityManagerInterface $em): Response
    {
        $schemaTool = new SchemaTool($em);
        $metadata = $em->getClassMetadata(Person::class);

        try {
            $schemaTool->updateSchema([$metadata], true); // true = safe update
            $message = '✅ Table "persons" created or already exists.';
        } catch (\Exception $e) {
            $message = '❌ Error: ' . $e->getMessage();
        }

        // Use bundle-style template path
        return $this->render('ex01/result.html.twig', [
            'message' => $message,
        ]);
    }
}
