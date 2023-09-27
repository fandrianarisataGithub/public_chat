<?php

namespace App\Entity;

use App\Repository\DemandFriendFromRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DemandFriendFromRepository::class)]
class DemandFriendFrom
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'demandFriendFroms')]
    private ?User $user = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    #[ORM\OneToMany(mappedBy: 'demandFriendFrom', targetEntity: DemandFriendFor::class)]
    private Collection $demandFriendFors;

    public function __construct()
    {
        $this->demandFriendFors = new ArrayCollection();
    }

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

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Collection<int, DemandFriendFor>
     */
    public function getDemandFriendFors(): Collection
    {
        return $this->demandFriendFors;
    }

    public function addDemandFriendFor(DemandFriendFor $demandFriendFor): static
    {
        if (!$this->demandFriendFors->contains($demandFriendFor)) {
            $this->demandFriendFors->add($demandFriendFor);
            $demandFriendFor->setDemandFriendFrom($this);
        }

        return $this;
    }

    public function removeDemandFriendFor(DemandFriendFor $demandFriendFor): static
    {
        if ($this->demandFriendFors->removeElement($demandFriendFor)) {
            // set the owning side to null (unless already changed)
            if ($demandFriendFor->getDemandFriendFrom() === $this) {
                $demandFriendFor->setDemandFriendFrom(null);
            }
        }

        return $this;
    }
}
