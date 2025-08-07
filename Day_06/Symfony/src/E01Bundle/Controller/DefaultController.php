<?php

namespace App\E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/e01", name="e01_home")
     */
    public function index(): Response
    {
        return $this->render('e01/index.html.twig', [
            'message' => 'Welcome to E01Bundle!'
        ]);
    }
}
