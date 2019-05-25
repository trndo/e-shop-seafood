<?php

namespace App\Controller\admin;

use App\Entity\User;
use App\Form\AdminCreateType;
use App\Model\AdminModel;
use App\Model\UserRegistrationModel;
use App\Service\EntityService\UserService\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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

    /**
     * @Route(path="/lipadmin/admins/create", name="admin_create")
     *
     * @param Request $request
     * @return Response
     */
    public function createAdmin(Request $request)
    {
        $user = new AdminModel();
        $form = $this->createForm(AdminCreateType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('admin/users/createAdmin.html.twig', ['form' => $form->createView()]);
    }
}