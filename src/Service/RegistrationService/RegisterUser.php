<?php


namespace App\Service\RegistrationService;

use App\Entity\User;
use App\Model\AdminModel;
use App\Model\ConfirmationModelAdmin;
use App\Model\OrderModel;
use App\Model\UserRegistrationModel;
use App\Service\MailService\MailSenderInterface;
use App\Service\TokenService\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterUser implements RegisterUserInterface
{
    private $passwordEncoder;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var MailSenderInterface
     */
    private $mailSender;
    /**
     * @var SessionInterface
     */
    private $session;

    /**
     * RegisterUser constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param MailSenderInterface $mailSender
     * @param SessionInterface $session
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $entityManager, MailSenderInterface $mailSender, SessionInterface $session)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->mailSender = $mailSender;
        $this->session = $session;
    }

    /**
     * @param UserRegistrationModel $model
     * @return User
     * @throws \Exception
     */
    public function registerUser(UserRegistrationModel $model): User
    {
        $user = new User();

        $user->setEmail($model->getEmail())
             ->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $model->getPassword()
                ))
            ->setName($model->getName())
            ->setSurname($model->getSurname())
            ->setAddress($model->getAddress())
            ->setPhone($model->getPhone())
            ->setToken(TokenGenerator::generateToken(
                $this->entityManager->getRepository(User::class)->findTokens(),20
            ))
            ->setRegistrationStatus(false)
            ->setUniqueId((Uuid::uuid1())->toString());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->mailSender->sendMessage($user);

        return $user;

    }

    public function registerAdmin(AdminModel $model): User
    {
        $admin = new User();

        $admin->setName($model->getName())
            ->setEmail($model->getEmail())
            ->setPhone($model->getPhone())
            ->setSurname($model->getSurname())
            ->setPassword(($this->passwordEncoder->encodePassword(
                $admin,
                $model->getPassword())
            ))
            ->setRoles([
                $model->getRole()
            ])
            ->setToken(TokenGenerator::generateToken(
                $this->entityManager->getRepository(User::class)->findTokens(),60
            ))
            ->setRegistrationStatus(false);

            $this->entityManager->persist($admin);
            $this->entityManager->flush();

            $this->mailSender->sendAdminMessage($admin);

            return $admin;
    }

    /**
     * @param User $user
     */
    public function confirmUser(User $user): void
    {
        $user->setToken(null)
            ->setRegistrationStatus(true);

        $this->entityManager->flush();
    }

    public function getRegisterAdminData(ConfirmationModelAdmin $model, User $admin): User
    {
        $admin->setName($model->getName())
             ->setSurname($model->getSurname())
             ->setPhone($model->getPhone())
             ->setPassword($this->passwordEncoder->encodePassword(
                 $admin,
                 $model->getPassword()
             ));


            return $admin;
    }

    public function registerUnknownUser(OrderModel $orderModel): User
    {
        $user = new User();

        $user->setEmail($orderModel->getEmail())
            ->setPhone($orderModel->getPhoneNumber())
            ->setAddress($orderModel->getAddress())
            ->setName($orderModel->getName())
            ->setSurname($orderModel->getSurname())
            ->setRegistrationStatus(true)
            ->setUniqueId((Uuid::uuid1())->toString());

        $userTemporaryPass = $this->createShortPass($orderModel->getEmail(),$orderModel->getName(),6);
        $user->setPassword($this->passwordEncoder->encodePassword(
                $user,
                $userTemporaryPass
        ));

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->mailSender->sendAboutUnknownRegistration($user,$userTemporaryPass);

        $this->session->set('userEmail', $user->getEmail());
        $this->session->set('userPass',$userTemporaryPass);

        return $user;
    }

    private function createShortPass(string $email, string $name, int $numberOfSymbols): string
    {
        $userData = $email.$name;
        $randomSymbols = str_shuffle($userData);

        return \substr(\md5($randomSymbols),0,$numberOfSymbols);

    }


}