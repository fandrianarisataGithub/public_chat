<?php

namespace App\Entity\Traits;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\Types;

trait EntityTimestamp
{
    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updatedAt = null;

    
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Get the value of updatedAt
     */ 
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    #[ORM\PrePersist]
    public function prePersist()
    {
        $this->createdAt = new \DateTimeImmutable();
    }
    #[ORM\PreUpdate]
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }
}
