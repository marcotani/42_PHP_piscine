<?php
namespace App\ex11\Controller;

use Doctrine\DBAL\Connection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class Ex11Controller extends AbstractController
{
    #[Route('/ex11', name: 'ex11_home')]
    public function index(Request $request, Connection $conn): Response
    {
        // Get filter and sort params
        $name = $request->query->get('name', '');
        $sort = $request->query->get('sort', 'name');
        $order = $request->query->get('order', 'asc');

        // Validate sort/order
    $allowedSort = ['id', 'name', 'birthdate', 'city', 'account_number'];
        $allowedOrder = ['asc', 'desc'];
        if (!in_array($sort, $allowedSort)) $sort = 'name';
        if (!in_array($order, $allowedOrder)) $order = 'asc';

        // Build SQL with join, filter, sort
    $sql = "SELECT p.id, p.name, p.birthdate, a.city, b.account_number
        FROM ex09_person p
        LEFT JOIN ex09_address a ON a.person_id = p.id
        LEFT JOIN ex09_bank_account b ON b.person_id = p.id
        WHERE (:name = '' OR p.name LIKE :nameLike)
        ORDER BY ".($sort === 'id' ? 'p.id' : ($sort === 'account_number' ? 'b.account_number' : $sort))." $order";
        $params = [
            'name' => $name,
            'nameLike' => "%$name%"
        ];
        $results = $conn->fetchAllAssociative($sql, $params);

        return $this->render('ex11/home.html.twig', [
            'results' => $results,
            'name' => $name,
            'sort' => $sort,
            'order' => $order,
        ]);
    }
}
