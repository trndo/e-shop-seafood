<?php


namespace App\Model;

use App\Validator\UniqueUser;
use Symfony\Component\Validator\Constraints as Assert;


class UserRegistrationModel
{
    /**
     * @Assert\NotBlank(message="Пожалуйста, введите email!")
     * @Assert\Email()
     *
     *
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Введите пароль")
     * @Assert\Length(min=5, minMessage="Этот пароль короткий")
     */
    private $password;

    /**
     * @Assert\NotBlank(message="Введите ваше имя")
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Введите вашу фамилию")
     */
    private $surname;

    /**
     * @Assert\NotBlank(message="Укажите ваш адрес")
     */
    private $address;

    /**
     * @Assert\NotBlank(message="Укажите ваш телефон")
     */
    private $phone;


    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail($email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName($name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname($surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress($address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }


}