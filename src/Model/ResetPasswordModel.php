<?php


namespace App\Model;
use Symfony\Component\Validator\Constraints as Assert;


class ResetPasswordModel
{
    /**
     * @var string|null
     */
    private $oldPassword;

    /**
     * @var string|null
     */
    private $password;

    /**
     * @var string|null
     */
    private $email;

    /**
     * @return string|null
     */
    public function getOldPassword(): ?string
    {
        return $this->oldPassword;
    }

    /**
     * @param string|null $oldPassword
     * @return ResetPasswordModel
     */
    public function setOldPassword(?string $oldPassword): self
    {
        $this->oldPassword = $oldPassword;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string|null $password
     * @return ResetPasswordModel
     */
    public function setPassword(?string $password): self
    {
        $this->password = $password;

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
     * @param string|null $email
     * @return ResetPasswordModel
     */
    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }




}