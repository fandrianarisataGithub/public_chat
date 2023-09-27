<?php

namespace App\Entity;

use App\Repository\DemandFriendForRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandFriendForRepository::class)]
class DemandFriendFor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demandFriendFors')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'demandFriendFors')]
    private ?DemandFriendFrom $demandFriendFrom = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getDemandFriendFrom(): ?DemandFriendFrom
    {
        return $this->demandFriendFrom;
    }

    public function setDemandFriendFrom(?DemandFriendFrom $demandFriendFrom): static
    {
        $this->demandFriendFrom = $demandFriendFrom;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }
}
