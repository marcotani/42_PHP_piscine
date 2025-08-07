<?php
namespace App\ex03\Controller;

use App\ex03\Entity\Ex03;
use App\ex03\Form\Ex03Type;
use App\ex03\Entity\Ex03Repository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class Ex03Controller extends AbstractController
{
    /**
     * @Route("/ex03/form", name="ex03_form")
     */
    public function form(Request $request, Ex03Repository $repo): Response
    {
        $ex03 = new Ex03();
        $form = $this->createForm(Ex03Type::class, $ex03);
        $form->handleRequest($request);
        $error = null;
        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $repo->save($ex03);
                return $this->redirectToRoute('ex03_list');
            } catch (\Doctrine\DBAL\Exception\UniqueConstraintViolationException $e) {
                $error = 'Username or Email already exists.';
            }
        }
        return $this->render('ex03/form.html.twig', [
            'form' => $form->createView(),
            'error' => $error,
        ]);
    }

    /**
     * @Route("/ex03/list", name="ex03_list")
     */
    public function list(Ex03Repository $repo): Response
    {
        $entities = $repo->findAllOrdered();
        return $this->render('ex03/list.html.twig', [
            'entities' => $entities,
        ]);
    }
}
