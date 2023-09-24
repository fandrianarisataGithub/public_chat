<?php

namespace App\Entity;

use App\Entity\Conversation;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Message
{
    //Pour les createdAt et updatedat
    use Traits\EntityTimestamp;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $content = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?User $messageOwner = null;

    #[ORM\ManyToOne(inversedBy: 'messages')]
    private ?Conversation $conversation = null;

    private $isMyMessage;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getMessageOwner(): ?User
    {
        return $this->messageOwner;
    }

    public function setMessageOwner(?User $messageOwner): static
    {
        $this->messageOwner = $messageOwner;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * Get the value of isMyMessage
     */ 
    public function getIsMyMessage()
    {
        return $this->isMyMessage;
    }

    /**
     * Set the value of isMyMessage
     *
     * @return  self
     */ 
    public function setIsMyMessage($isMyMessage)
    {
        $this->isMyMessage = $isMyMessage;

        return $this;
    }
}
