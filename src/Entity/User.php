<?php

namespace App\Entity;

use App\Entity\DemandFriendFor;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
#[ORM\HasLifecycleCallbacks]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use Traits\EntityTimestamp;
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $username = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private $isVerified = false;

    #[ORM\OneToMany(mappedBy: 'participant', targetEntity: Participation::class)]
    private Collection $participations;

    #[ORM\OneToMany(mappedBy: 'messageOwner', targetEntity: Message::class)]
    private Collection $messages;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandFriendFrom::class)]
    private Collection $demandFriendFroms;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: DemandFriendFor::class)]
    private Collection $demandFriendFors;

    public function __construct()
    {
        $this->participations = new ArrayCollection();
        $this->messages = new ArrayCollection();
        $this->demandFriendFroms = new ArrayCollection();
        $this->demandFriendFors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    /**
     * Get the value of username
     */ 
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set the value of username
     *
     * @return  self
     */ 
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return Collection<int, Participation>
     */
    public function getParticipations(): Collection
    {
        return $this->participations;
    }

    public function addParticipation(Participation $participation): static
    {
        if (!$this->participations->contains($participation)) {
            $this->participations->add($participation);
            $participation->setParticipant($this);
        }

        return $this;
    }

    public function removeParticipation(Participation $participation): static
    {
        if ($this->participations->removeElement($participation)) {
            // set the owning side to null (unless already changed)
            if ($participation->getParticipant() === $this) {
                $participation->setParticipant(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setMessageOwner($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getMessageOwner() === $this) {
                $message->setMessageOwner(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, DemandFriendFrom>
     */
    public function getDemandFriendFroms(): Collection
    {
        return $this->demandFriendFroms;
    }

    public function addDemandFriendFrom(DemandFriendFrom $demandFriendFrom): static
    {
        if (!$this->demandFriendFroms->contains($demandFriendFrom)) {
            $this->demandFriendFroms->add($demandFriendFrom);
            $demandFriendFrom->setUser($this);
        }

        return $this;
    }

    public function removeDemandFriendFrom(DemandFriendFrom $demandFriendFrom): static
    {
        if ($this->demandFriendFroms->removeElement($demandFriendFrom)) {
            // set the owning side to null (unless already changed)
            if ($demandFriendFrom->getUser() === $this) {
                $demandFriendFrom->setUser(null);
            }
        }

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
            $demandFriendFor->setUser($this);
        }

        return $this;
    }

    public function removeDemandFriendFor(DemandFriendFor $demandFriendFor): static
    {
        if ($this->demandFriendFors->removeElement($demandFriendFor)) {
            // set the owning side to null (unless already changed)
            if ($demandFriendFor->getUser() === $this) {
                $demandFriendFor->setUser(null);
            }
        }

        return $this;
    }
}
