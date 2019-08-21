<?php


namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;

class ConfirmationModelAdmin
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
     * @var string $phone
     */
    private $phone;

    /**
     * @Assert\NotBlank(message="Это поле должно быть заполнено")
     * @Assert\Length(min="5", minMessage="Минимальная длина пароля 5 символов")
     * @var string $password
     */
    private $password;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return ConfirmationModelAdmin
     */
    public function setName(?string $name): ConfirmationModelAdmin
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getSurname(): string
    {
        return $this->surname;
    }

    /**
     * @param string $surname
     * @return ConfirmationModelAdmin
     */
    public function setSurname(?string $surname): ConfirmationModelAdmin
    {
        $this->surname = $surname;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhone(): ?string
    {
        return $this->phone;
    }

    /**
     * @param string $phone
     * @return ConfirmationModelAdmin
     */
    public function setPhone(?string $phone): ConfirmationModelAdmin
    {
        $this->phone = $phone;
        return $this;
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     * @return ConfirmationModelAdmin
     */
    public function setPassword(?string $password): ConfirmationModelAdmin
    {
        $this->password = $password;
        return $this;
    }


}