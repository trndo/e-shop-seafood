<?php

namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class AdminModel
{
    /**
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @var string $name
     */
    private $name;

    /**
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @var string $surname
     */
    private $surname;

    /**
     * @Assert\Email(message = "Проверьте правильность написание почты '{{ value }}'")
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @var string $email
     */
    private $email;

    /**
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @var string $phone
     */
    private $phone;

    /**
     * @var mixed
     */
    private $role;

    /**
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @Assert\Length(min="5", minMessage="Минимальная длина пароля 5 символов")
     * @var string $password
     */
    private $password;

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }


    /**
     * @param $password
     * @return AdminModel
     */
    public function setPassword($password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }


    /**
     * @param string|null $name
     * @return AdminModel
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getSurname(): ?string
    {
        return $this->surname;
    }

    /**
     * @param string|null $surname
     * @return AdminModel
     */
    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }


    /**
     * @return string|null
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string|null $phone
     * @return AdminModel
     */
    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getRole(): ?string
    {
        return $this->role;
    }

    /**
     * @param $role
     * @return AdminModel
     */
    public function setRole($role): self
    {
        $this->role = $role;

        return $this;
    }


}