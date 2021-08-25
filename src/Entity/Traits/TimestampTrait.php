<?php

declare(strict_types=0);

namespace App\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Trait DateTrait
 * @package App\Entity\Traits
 */
trait TimestampTrait
{
    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @var DateTime|null
     * @ORM\Column(type="datetime")
     */
    private $updatedAt;

    /**
     * @return DateTime|null
     */
    public function getCreatedAt(): ?DateTime
    {
        return $this->createdAt;
    }

    /**
     * @return self
     * @ORM\PrePersist()
     */
    public function setCreatedAt(): self
    {
        // Make sure we don't ever overwrite any existing createdAt values...
        if ($this->createdAt === null) {
            try {
                $this->createdAt = new DateTime();
            }
            catch (\Exception $e) {
                // Ignore this
            }
        }
        return $this;
    }

    /**
     * @return DateTime|null
     */
    public function getUpdatedAt(): ?DateTime
    {
        return $this->updatedAt;
    }

    /**
     * @return self
     * @ORM\PrePersist()
     * @ORM\PreUpdate()
     */
    public function setUpdatedAt(): self
    {
        try {
            $this->updatedAt = new DateTime();
        }
        catch (\Exception $e) {
            // Ignore this
        }
        return $this;
    }
}
