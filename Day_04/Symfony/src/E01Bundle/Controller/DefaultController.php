<?php

namespace E01Bundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    private $categories = [
        'controller', 'routing', 'templating', 'doctrine',
        'testing', 'validation', 'forms', 'security',
        'cache', 'translations', 'services'
    ];

    /**
     * This matches exactly "/e01" and "/e01/"
     *
     * @Route("/", name="e01_home")
     */
    public function indexAction()
    {
        return $this->render('E01Bundle:Default:index.html.twig', [
            'categories' => $this->categories
        ]);
    }

    /**
     * This matches "/e01/something"
     *
     * @Route("/{category}", name="e01_category")
     */
    public function showAction($category)
    {
        if (!in_array($category, $this->categories)) {
            throw $this->createNotFoundException();
        }

        return $this->render('E01Bundle:Default:' . $category . '.html.twig');
    }
}

?>
