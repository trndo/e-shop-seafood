<?php


namespace App\Controller\admin;

use App\Entity\User;
use App\Service\EntityService\UserService\UserServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminBonusesController extends AbstractController
{
    /**
     * @Route("/lipadmin/users/showBonuses/{id}", name="showBonuses")
     *
     * @param UserServiceInterface $userService
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function showBonuses(UserServiceInterface $userService, User $user, Request $request): Response
    {
        return $this->render('elements/bonus_modal.html.twig',[
            'user' => $user
        ]);
    }

    /**
     * @Route("/lipadmin/users/editBonuses/{id}", name="editUserBonuses")
     *
     * @param UserServiceInterface $userService
     * @param User $user
     * @param Request $request
     * @return Response
     */
    public function editBonuses(UserServiceInterface $userService, User $user, Request $request): Response
    {
        $newBonuses = $request->request->get('bonuses');
        $oldBonuses = $user->getBonuses();

        if ($newBonuses != $oldBonuses && $newBonuses !=null) {
            $userService->saveBonuses($user,$newBonuses);
        }

        return $this->redirectToRoute('users');
    }
}