<?php
/**
 * Created by PhpStorm.
 * User: raulnet
 * Date: 13/05/18
 * Time: 11:22.
 */

namespace Happy\Entity\Traits;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class TimestampableTrait.
 */
trait TimestampableTrait
{
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_creation", type="datetime", nullable=false)
     */
    private $dateCreation;
    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_update", type="datetime", nullable=false)
     */
    private $dateUpdate;
    /**
     * @var \DateTime|null
     *
     * @ORM\Column(name="date_deleted", type="datetime", nullable=true)
     */
    private $dateDeleted;

    /**
     * Auto-update createdAt and updatedAt automatically.
     *
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function autoUpdate(): void
    {
        if (null === $this->dateCreation) {
            $this->dateCreation = new \DateTime('now');
        }
        $this->dateUpdate = new \DateTime('now');
    }

    /**
     * @return \DateTime
     */
    public function getDateCreation(): ?\DateTime
    {
        return $this->dateCreation;
    }

    /**
     * @param \DateTime $dateCreation
     */
    public function setDateCreation(\DateTime $dateCreation): void
    {
        $this->dateCreation = $dateCreation;
    }

    /**
     * @return \DateTime
     */
    public function getDateUpdate(): ?\DateTime
    {
        return $this->dateUpdate;
    }

    /**
     * @param \DateTime $dateUpdate
     */
    public function setDateUpdate(\DateTime $dateUpdate): void
    {
        $this->dateUpdate = $dateUpdate;
    }

    /**
     * @return \DateTime|null
     */
    public function getDateDeleted(): ?\DateTime
    {
        return $this->dateDeleted;
    }

    /**
     * @param \DateTime|null $dateTime
     */
    public function setDateDeleted(?\DateTime $dateTime): void
    {
        $this->dateDeleted = $dateTime;
    }

    /**
     * @return bool
     */
    public function isDeleted(): bool
    {
        return (bool) $this->getDateDeleted();
    }
}
