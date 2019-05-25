<?php

namespace App\Controller\admin;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminController
 * @package App\Controller\admin
 *
 */
class AdminController extends AbstractController
{
    /**
     * @Route(path="/lipadmin", name="admin")
     *
     * @return Response
     */
    public function homeAdmin(): Response
    {
        return $this->render('admin/admin.html.twig');
    }

}