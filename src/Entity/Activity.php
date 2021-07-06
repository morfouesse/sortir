<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ActivityRepository::class)
 */
class Activity
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $startDateTime;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $inscriptionLimitDate;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $maxInscriptionsNb;

    /**
     * @ORM\Column(type="text")
     */
    private $activityInfo;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $location;

    /**
     * @ORM\ManyToOne(targetEntity=Campus::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $campus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="activitiesOwned")
     * @ORM\JoinColumn(nullable=false)
     */
    private $userOwner;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="activities")
     */
    private $users;

    /**
     * @ORM\ManyToOne(targetEntity=State::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     */
    private $state;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        // InscriptionLimitDate default value is StartDateTime
        $this->setInscriptionLimitDate($this->getStartDateTime());
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getStartDateTime(): ?\DateTimeInterface
    {
        return $this->startDateTime;
    }

    public function setStartDateTime(\DateTimeInterface $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getInscriptionLimitDate(): ?\DateTimeInterface
    {
        return $this->inscriptionLimitDate;
    }

    public function setInscriptionLimitDate(?\DateTimeInterface $inscriptionLimitDate): self
    {
        $this->inscriptionLimitDate = $inscriptionLimitDate;

        return $this;
    }

    public function getMaxInscriptionsNb(): ?int
    {
        return $this->maxInscriptionsNb;
    }

    public function setMaxInscriptionsNb(?int $maxInscriptionsNb): self
    {
        $this->maxInscriptionsNb = $maxInscriptionsNb;

        return $this;
    }

    public function getActivityInfo(): ?string
    {
        return $this->activityInfo;
    }

    public function setActivityInfo(string $activityInfo): self
    {
        $this->activityInfo = $activityInfo;

        return $this;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getCampus(): ?Campus
    {
        return $this->campus;
    }

    public function setCampus(?Campus $campus): self
    {
        $this->campus = $campus;

        return $this;
    }

    public function getUserOwner(): ?User
    {
        return $this->userOwner;
    }

    public function setUserOwner(?User $userOwner): self
    {
        $this->userOwner = $userOwner;

        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addActivity($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeActivity($this);
        }

        return $this;
    }

    public function getState(): ?State
    {
        return $this->state;
    }

    public function setState(?State $state): self
    {
        $this->state = $state;

        return $this;
    }
}
