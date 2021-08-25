<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use App\Repository\OrderRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=OrderRepository::class)
 * @ORM\Table(name="`order`")
 * @ORM\HasLifecycleCallbacks()
 */
class Order
{
    const STATUS_AVAILABLE = 0;
    const STATUS_BOOKED = 1;
    const STATUS_RESERVED = 2;
    const STATUS_COMPLETED = 3;
    const STATUS_CANCELLED = 4;
    const STATUS_UNAVAILABLE = 5;

    use IdTrait;
    use TimestampTrait;

    /**
     * @var User
     * @ORM\ManyToOne (targetEntity="App\Entity\User", inversedBy="orders")
     */
    private $user;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="therapistOrders")
     */
    private $therapist;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var float
     * @ORM\Column(type="float")
     */
    private $price;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $duration;

    /**
     * @var int
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @var $dateTime
     * @ORM\Column(type="datetime")
     */
    private $dateTime;

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @param User $user
     * @return $this
     */
    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @param User $user
     * @return User
     */
    public function getTherapist(User $user)
    {
        return $this->therapist;
    }

    /**
     * @param User $therapist
     * @return $this
     */
    public function setTherapist(User $therapist): self
    {
        $this->therapist = $therapist;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @param string $title
     * @return Order
     */
    public function setTitle(string $title): Order
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param float $price
     * @return Order
     */
    public function setPrice(float $price): Order
    {
        $this->price = $price;
        return $this;
    }

    /**
     * @return int
     */
    public function getDuration(): int
    {
        return $this->duration;
    }

    /**
     * @param int $duration
     * @return Order
     */
    public function setDuration(int $duration): Order
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDateTime(): \DateTime
    {
        return $this->dateTime;
    }

    /**
     * @param \DateTime $dateTime
     * @return Order
     */
    public function setDateTime(\DateTime $dateTime): self
    {
        $this->dateTime = $dateTime;
        return $this;
    }

}
