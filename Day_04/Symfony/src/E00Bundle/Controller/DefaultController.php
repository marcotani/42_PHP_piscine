<?php

namespace E00Bundle\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/firstpage", name="e00_firstpage")
     */
    public function firstPageAction()
    {
        return new Response('Hello world!');
    }
}

