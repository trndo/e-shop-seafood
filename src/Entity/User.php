<?php

namespace App\Entity;

use App\Collection\OrdersCollection;
use App\Repository\OrderInfoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Gedmo\Timestampable\Traits\TimestampableEntity;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @UniqueEntity(
 *     fields={"email","phone"},
 *     message="Такой пользователь уже зарегистрирован, пожалуйста попробуйте заново"
 * )
 */
class User implements UserInterface
{
    use TimestampableEntity;

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string", nullable=true)
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $surname;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $address;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $bonuses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true, unique=true)
     * @Gedmo\Slug(fields={"name","surname","id"})
     */
    private $slug;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $token;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $registrationStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $googleId;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $facebookId;

    /**
     * @ORM\Column(type="bigint", nullable=true)
     */
    private $instagramId;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $passToken;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\OrderInfo", mappedBy="user")
     */
    private $orderInfos;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $coordinates;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $uniqueId;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $registerWithUniqueId;

    public function __construct()
    {
        $this->orderInfos = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSurname(): ?string
    {
        return $this->surname;
    }

    public function setSurname(?string $surname): self
    {
        $this->surname = $surname;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBonuses(): ?int
    {
        return $this->bonuses;
    }

    public function setBonuses(?int $bonuses): self
    {
        $this->bonuses = $bonuses;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getRegistrationStatus(): ?bool
    {
        return $this->registrationStatus;
    }

    public function setRegistrationStatus(?bool $registrationStatus): self
    {
        $this->registrationStatus = $registrationStatus;

        return $this;
    }

    public function getGoogleId(): ?string
    {
        return $this->googleId;
    }

    public function setGoogleId(?string $googleId): self
    {
        $this->googleId = $googleId;

        return $this;
    }

    public function getFacebookId(): ?int
    {
        return $this->facebookId;
    }

    public function setFacebookId(?int $facebookId): self
    {
        $this->facebookId = $facebookId;

        return $this;
    }

    public function getInstagramId(): ?int
    {
        return $this->instagramId;
    }

    public function setInstagramId(?int $instagramId): self
    {
        $this->instagramId = $instagramId;

        return $this;
    }

    public function getPassToken(): ?string
    {
        return $this->passToken;
    }

    public function setPassToken(?string $passToken): self
    {
        $this->passToken = $passToken;

        return $this;
    }

    /**
     * @return Collection|OrderInfo[]
     */
    public function getOrderInfos(): Collection
    {
        return $this->orderInfos;
    }

    public function addOrderInfo(OrderInfo $orderInfo): self
    {
        if (!$this->orderInfos->contains($orderInfo)) {
            $this->orderInfos[] = $orderInfo;
            $orderInfo->setUser($this);
        }

        return $this;
    }

    public function removeOrderInfo(OrderInfo $orderInfo): self
    {
        if ($this->orderInfos->contains($orderInfo)) {
            $this->orderInfos->removeElement($orderInfo);
            // set the owning side to null (unless already changed)
            if ($orderInfo->getUser() === $this) {
                $orderInfo->setUser(null);
            }
        }

        return $this;
    }

    public function getCoordinates(): ?string
    {
        return $this->coordinates;
    }

    public function setCoordinates(?string $coordinates): self
    {
        $this->coordinates = $coordinates;

        return $this;
    }

    public function getUniqueId(): ?string
    {
        return $this->uniqueId;
    }

    public function setUniqueId(?string $uniqueId): self
    {
        $this->uniqueId = $uniqueId;

        return $this;
    }

    public function getRegisterWithUniqueId(): ?bool
    {
        return $this->registerWithUniqueId;
    }

    public function setRegisterWithUniqueId(?bool $registerWithUniqueId): self
    {
        $this->registerWithUniqueId = $registerWithUniqueId;

        return $this;
    }

    public function generateUniqueId(int $length): string
    {
        return substr(uniqid(md5(new \DateTimeImmutable("now"))),0, $length);
    }





}
