<?php

namespace App\Controller\admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route(path="/lipadmin", name="admin")
     *
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('admin/admin.html.twig');
    }
}