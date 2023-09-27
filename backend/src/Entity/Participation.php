<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    private ?Conversation $conversations = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    private ?User $participant = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getConversations(): ?Conversation
    {
        return $this->conversations;
    }

    public function setConversations(?Conversation $conversations): static
    {
        $this->conversations = $conversations;

        return $this;
    }

    public function getParticipant(): ?User
    {
        return $this->participant;
    }

    public function setParticipant(?User $participant): static
    {
        $this->participant = $participant;

        return $this;
    }
}
