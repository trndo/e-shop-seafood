<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Service\EntityService\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class UserController
 * @package App\Controller\admin
 */
class UserController extends AbstractController
{
    /**
     * @Route(path="/lipadmin/admins", name="admins")
     *
     * @param UserService $userService
     * @return Response
     */
    public function adminUsers(UserService $userService): Response
    {
        $admins = $userService->getAdmins();
        return $this->render('admin/users/admins.html.twig', ['admins' => $admins]);
    }
}