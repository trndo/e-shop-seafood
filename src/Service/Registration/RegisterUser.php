<?php


namespace App\Service\Registration;

use App\Entity\User;
use App\Model\UserRegistrationModel;
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
     * RegisterUser constructor.
     * @param $passwordEncoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder,EntityManagerInterface $entityManager)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->entityManager = $entityManager;
    }

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
            ->setPhone($model->getPhone());

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;

    }

}