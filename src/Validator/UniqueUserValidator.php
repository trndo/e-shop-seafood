<?php

namespace App\Validator;

use App\Repository\UserRepository;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class UniqueUserValidator extends ConstraintValidator
{
    /**
     * @var UserRepository
     */
    private $repository;
    /**
     * @var Security
     */
    private $security;

    /**
     * UniqueUserValidator constructor.
     * @param UserRepository $repository
     * @param Security $security
     */
    public function __construct(UserRepository $repository, Security $security)
    {
        $this->repository = $repository;
        $this->security = $security;
    }

    public function validate($value, Constraint $constraint)
    {
        /* @var $constraint \App\Validator\UniqueUser */

        $existingUser = $this->repository->findOneBy([
            'email' => $value
        ]);

        if (!$existingUser) {
            return;
        }
        $currentUser = $this->security->getUser();
        if ($currentUser && $currentUser === $existingUser) {
            return;
        }

        $this->context->buildViolation($constraint->message)
            ->setParameter('{{ value }}', $value)
            ->addViolation();
    }
}
