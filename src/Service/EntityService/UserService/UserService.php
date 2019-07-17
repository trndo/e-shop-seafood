<?php

namespace App\Service\EntityService\UserService;

use App\Collection\UserCollection;
use App\Entity\User;
use App\Mapper\UserMapper;
use App\Model\AdminModel;
use App\Repository\UserRepository;
use App\Service\MailService\MailSenderInterface;
use App\Service\TokenService\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserService implements UserServiceInterface
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MailSenderInterface
     */
    private $mailSender;
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * UserService constructor.
     * @param UserRepository $repository
     * @param EntityManagerInterface $entityManager
     * @param MailSenderInterface $mailSender
     * @
     * @param UserPasswordEncoderInterface $passwordEncoder
     */
    public function __construct(UserRepository $repository, EntityManagerInterface $entityManager, MailSenderInterface $mailSender, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->mailSender = $mailSender;
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @return UserCollection
     */
    public function getAdmins(): UserCollection
    {
        return new UserCollection($this->repository->findAdmins());
    }

    /**
     * @param string $token
     * @return User|null
     */
    public function getUserByToken(?string $token): ?User
    {
        return $this->repository->findOneBy([
            'token' => $token
        ]);
    }

    /**
     * @param User $user
     */
    public function deleteUser(?User $user): void
    {
        if ($user instanceof User) {
            $this->repository->delete($user);
        }
    }

    public function saveUser(?User $user): void
    {
        if ($user instanceof User) {
            $this->repository->save($user);
        }
    }

    public function getUsers(): UserCollection
    {
        return new UserCollection($this->repository->findUsers());
    }

    public function deleteUserById(?User $user): void
    {
        if ($user instanceof User){
            $this->repository->delete($user);
        }
    }

    public function saveBonuses(?User $user,?int $bonuses): void
    {
        $user->setBonuses($bonuses);
        $this->saveUser($user);
    }

    public function resetPassword(?User $user): User
    {
        $user->setPassToken(TokenGenerator::generateToken(
            $this->entityManager->getRepository(User::class)->findTokens(),60
        ));

        $this->entityManager->flush();

        $this->mailSender->sendResetUserPassword($user);

        return $user;
    }

    public function findUserByEmail(?string $email): User
    {
        if ($email != null) {
           return $user = $this->repository->findOneBy(['email' => $email]);
        }
        return null;
    }

    /**
     * @param $user
     * @param $newPassword
     * @param $oldPassword
     */
    public function resetOldPassword(?User $user, ?string $newPassword, ?string  $oldPassword): void
    {
        // TODO: Implement resetOldPassword() method.
    }

    /**
     * @param $user
     * @param $password
     */
    public function addNewPassword(?User $user,?string $password): void
    {
        if ($password != null) {
            $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $password
            ));
            $user->setPassToken(null);
            $this->entityManager->flush();
        }
    }

    public function getUserByPassToken(?string $passToken): User
    {
        if ($passToken != null) {
            return $user = $this->repository->findOneBy(['passToken' => $passToken]);
        }
        return null;
    }
}