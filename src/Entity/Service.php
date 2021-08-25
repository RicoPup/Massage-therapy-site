<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="service")
 * @ORM\HasLifecycleCallbacks()
 */
class Service
{
    use IdTrait;
    use TimestampTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $title;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var float
     * @ORM\Column(type="string")
     */
    private $price;

    /**
     * @var int
     * @ORM\Column(type="string")
     */
    private $duration;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Therapist", inversedBy="services", cascade={"persist"})
     */
    private $therapists;

    public function __construct()
    {
        $this->therapists = new ArrayCollection();
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
     * @return Service
     */
    public function setTitle(string $title): Service
    {
        $this->title = $title;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return Service
     */
    public function setDescription(string $description): Service
    {
        $this->description = $description;
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
     * @return Service
     */
    public function setPrice(float $price): Service
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
     * @return Service
     */
    public function setDuration(int $duration): Service
    {
        $this->duration = $duration;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getTherapists(): Collection
    {
        return $this->therapists;
    }

    /**
     * @param ArrayCollection $therapists
     * @return Service
     */
    public function setTherapists(ArrayCollection $therapists): Service
    {
        $this->therapists = $therapists;
        return $this;
    }

    /**
     * @param Therapist $therapist
     * @return $this
     */
    public function addTherapist(Therapist $therapist): Service
    {
        if (!$this->therapists->contains($therapist)) {
            $this->therapists->add($therapist);
        }

        return $this;
    }

    /**
     * @param Therapist $therapist
     * @return $this
     */
    public function removeTherapist(Therapist $therapist): Service
    {
        if ($this->therapists->contains($therapist)) {
            $this->therapists->removeElement($therapist);
        }

        return $this;
    }
}