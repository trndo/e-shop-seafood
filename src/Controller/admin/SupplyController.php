<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SupplyController extends AbstractController
{
    /**
     * @Route(path="/lipadmin/supply", name="supply")
     *
     * @return Response
     */
    public function show(): Response
    {
        return $this->render('admin/supply/show.html.twig');
    }
}