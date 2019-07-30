<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FastOrderController extends AbstractController
{
    /**
     * @Route("/fastOrder", name="fast_order")
     *
     * @return Response
     */
    public function fastOrder(): Response
    {
        return $this->render('fastOrder.html.twig');
    }
}