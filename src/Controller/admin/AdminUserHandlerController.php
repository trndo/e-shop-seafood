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

class AdminUserHandlerController extends AbstractController
{
    /**
     * @Route("/lipadmin/users/delete/{id}", name="deleteUser")
     *
     * @param UserServiceInterface $userService
     * @param User $user
     * @return Response
     */
    public function deleteUser(UserServiceInterface $userService, User $user): Response
    {
        $userService->deleteUserById($user);

        return $this->redirectToRoute('users');
    }

    /**
     * @Route(path="/lipadmin/admins/create", name="admin_create")
     *
     * @param Request $request
     * @param RegisterUserInterface $registerUser
     * @return Response
     */
    public function createAdmin(Request $request,RegisterUserInterface $registerUser)
    {
        $model = new AdminModel();
        $form = $this->createForm(AdminCreateType::class, $model);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {

            $registerUser->registerAdmin($model);

            return $this->redirectToRoute('admins');
        }

        return $this->render('admin/users/createAdmin.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/lipadmin/admins/delete/{slug}", name="deleteAdminUser")
     *
     * @param UserServiceInterface $userService
     * @param User $user
     * @return Response
     */
    public function deleteAdminUser(UserServiceInterface $userService,User $user): Response
    {
        $userService->deleteUser($user);

        return $this->redirectToRoute('admins');
    }

    /**
     * @Route("/admin-confirmation/{token}", name="confirmAdmin")
     *
     * @param Request $request
     * @param RegisterUserInterface $registerUser
     * @param User $user
     * @return Response
     */
    public function confirmAdmin(Request $request, RegisterUserInterface $registerUser, User $user): Response
    {
        $model = UserMapper::entityToConfirmationAdminModel($user);

        $form  = $this->createForm(AdminRegistrationType::class, $model);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $admin = $registerUser->getRegisterAdminData($model, $user);

            $registerUser->confirmUser($admin);

            return $this->redirectToRoute('login');
        }

        return $this->render('security/admin_registration.html.twig',[
            'form' => $form->createView()
        ]);
    }
}