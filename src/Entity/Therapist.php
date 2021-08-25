<?php

namespace App\Entity;

use App\Entity\Traits\IdTrait;
use App\Entity\Traits\TimestampTrait;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="therapist")
 * @ORM\HasLifecycleCallbacks()
 */
class Therapist
{
    use IdTrait;
    use TimestampTrait;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="text")
     */
    private $bio;

    /**
     * @var ArrayCollection
     * @ORM\ManyToMany(targetEntity="App\Entity\Service", mappedBy="therapists", cascade={"persist"})
     */
    private $services;

    /**
     * @var array
     * @ORM\Column(type="array")
     */
    private $days;

    public function __construct()
    {
        $this->services = new ArrayCollection();
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Therapist
     */
    public function setName(string $name): Therapist
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return string
     */
    public function getBio(): string
    {
        return $this->bio;
    }

    /**
     * @param string $bio
     * @return Therapist
     */
    public function setBio(string $bio): Therapist
    {
        $this->bio = $bio;
        return $this;
    }

    /**
     * @return Collection
     */
    public function getServices(): Collection
    {
        return $this->services;
    }

    /**
     * @param ArrayCollection $services
     * @return Therapist
     */
    public function setServices(ArrayCollection $services): Therapist
    {
        $this->services = $services;
        return $this;
    }

    /**
     * @param Service $service
     * @return $this
     */
    public function addService(Service $service): Therapist
    {
        if (!$this->services->contains($service)) {
            $this->services->add($service);
        }

        return $this;
    }

    /**
     * @param Service $service
     * @return $this
     */
    public function removeService(Service $service): Therapist
    {
        if ($this->services->contains($service)) {
            $this->services->removeElement($service);
        }

        return $this;
    }

    /**
     * @return array
     */
    public function getDays(): array
    {
        return $this->days;
    }

    /**
     * @param array $days
     * @return Therapist
     */
    public function setDays(array $days): Therapist
    {
        $this->days = $days;
        return $this;
    }


}