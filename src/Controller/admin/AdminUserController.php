<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\AdminCreateType;
use App\Form\AdminRegistrationType;
use App\Mapper\UserMapper;
use App\Model\AdminModel;
use App\Service\EntityService\UserService\UserServiceInterface;
use App\Service\RegistrationService\RegisterUserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class AdminUserController
 * @package App\Controller\admin
 */
class AdminUserController extends AbstractController
{
    /**
     * @Route(path="/lipadmin/admins", name="admins")
     *
     * @param UserServiceInterface $userService
     * @return Response
     */
    public function adminUsers(UserServiceInterface $userService): Response
    {
        $admins = $userService->getAdmins();

        return $this->render('admin/users/admins.html.twig', [
            'admins' => $admins
        ]);
    }

    /**
     * @Route(path="/lipadmin/users", name="users")
     *
     * @param UserServiceInterface $userService
     * @return Response
     */
    public function showUsers(UserServiceInterface $userService): Response
    {
        $users = $userService->getUsers();

        return $this->render('admin/users/users.html.twig', [
            'users' => $users
        ]);
    }

}