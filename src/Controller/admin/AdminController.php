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
 * @IsGranted("ROLE_DEVELOPER")
 */
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