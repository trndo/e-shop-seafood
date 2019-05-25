<?php


namespace App\Service\Registration;

use App\Entity\User;
use App\Model\UserRegistrationModel;
use App\Service\Mail\MailSenderInterface;
use App\Service\Token\TokenGenerator;
use Doctrine\ORM\EntityManagerInterface;
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
     * RegisterUser constructor.
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param EntityManagerInterface $entityManager
     * @param MailSenderInterface $mailSender
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,
                                EntityManagerInterface $entityManager, MailSenderInterface $mailSender)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
        $this->mailSender = $mailSender;
    }

    /**
     * @param UserRegistrationModel $model
     * @return User
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
            ->setToken(TokenGenerator::generateToken($this->entityManager->getRepository(User::class)->findTokens()))
            ->setRegistrationStatus(false);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->mailSender->sendMessage($user);

        return $user;

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
}