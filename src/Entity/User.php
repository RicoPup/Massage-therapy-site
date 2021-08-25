<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Rollerworks\Component\PasswordStrength\Validator\Constraints as Rollerworks;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @UniqueEntity(fields={"email"}, message="This email address is already registered")
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class User implements UserInterface
{
    use IdTrait;
    use TimestampTrait;

    /**
     * @Assert\NotBlank
     * @ORM\Column(type="string")
     */
    private $firstName;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="string")
     */
    private $lastName;

    /**
     * @Assert\Email(
     *     message = "Please enter a valid email address."
     * )
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @Assert\NotBlank
     * @var string The hashed pass
     * @Rollerworks\PasswordStrength(minLength=7, minStrength=2)
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="user")
     */
    private $orders;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Order", mappedBy="therapist")
     */
    private $therapistOrders;

    public function __construct()
    {
        $this->orders = new ArrayCollection();
        $this->therapistOrders = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return User
     */
    public function setFirstName($firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return User
     */
    public function setLastName($lastName): self
    {
        $this->lastName = $lastName;
        return $this;
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
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @return mixed
     */
    public function getOrders(): Collection
    {
        return $this->orders;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function addOrder(Order $order): self
    {
        if (!$this->orders->contains($order)) {
            $this->orders->add($order);
        }
        return $this;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function removeOrder(Order $order): self
    {
        if ($this->orders->contains($order)) {
            $this->orders->removeElement($order);
        }
        return $this;
    }

    /**
     * @return mixed
     */
    public function getTherapistOrders(): Collection
    {
        return $this->therapistOrders;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function addTherapistOrder(Order $order): self
    {
        if (!$this->therapistOrders->contains($order)) {
            $this->therapistOrders->add($order);
        }
        return $this;
    }

    /**
     * @param Order $order
     * @return $this
     */
    public function removeTherapistOrder(Order $order): self
    {
        if ($this->therapistOrders->contains($order)) {
            $this->therapistOrders->removeElement($order);
        }
        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }
}
