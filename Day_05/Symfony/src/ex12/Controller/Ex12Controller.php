<?php
namespace App\ex12\Controller;

use App\ex09\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex12Controller extends AbstractController
{
    #[Route('/ex12', name: 'ex12_home')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $name = $request->query->get('name', '');
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');

    $allowedSort = ['id', 'name', 'birthdate', 'city', 'account_number'];
        $allowedOrder = ['asc', 'desc'];
        if (!in_array($sort, $allowedSort)) $sort = 'name';
        if (!in_array($order, $allowedOrder)) $order = 'asc';

        $repo = $em->getRepository(Person::class);
        $results = $repo->findWithJoinAndFilter($name, $sort, $order);

        return $this->render('ex12/home.html.twig', [
            'results' => $results,
            'name' => $name,
            'sort' => $sort,
            'order' => $order,
        ]);
    }
}
