<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @Assert\NotBlank(message="Ce champs doit être rempli")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotNull(
     *  message="Ce champs doit être rempli"
     * )
     * @Assert\Range(
     *      min = "now",
     *      max = "+1 years",
     *      notInRangeMessage="la date actuelle au minimum et un an de plus au maximum"
     * )
     */
    private $startDateTime;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotNull(
     *  message="Ce champs doit être rempli"
     * )
     * @Assert\Range(
     *      min=3,
     *      max= 1439,
     *      notInRangeMessage="Minimum {{ min }} minutes et {{ max }} minutes maximum",
     * )
     */
    private $duration;

    /**
     * @ORM\Column(type="date", nullable=false)
     * @Assert\NotNull(
     *  message="Ce champs doit être rempli"
     * )
     * @Assert\Range(
     *      min = "now",
     *      max = "+1 years",
     *      minMessage="date actuelle au minimum",
     *      maxMessage="Au plus tard dans 1 an"
     * )
     */
    private $inscriptionLimitDate;

    /**
     * @ORM\Column(type="integer", nullable=false)
     * @Assert\NotNull(
     *  message="Ce champs doit être rempli"
     * )
     * @Assert\Range(
     *      min=3,
     *      max= 60,
     *      notInRangeMessage ="Minimum {{ min }} inscrits et {{ max }} inscrits maximum",
     * )
     */
    private $maxInscriptionsNb;

    /**
     * @ORM\Column(type="text")
     * @Assert\NotBlank(message="Ce champs doit être rempli")
     */
    private $activityInfo;

    /**
     * @ORM\ManyToOne(targetEntity=Location::class, inversedBy="activities")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Ce champs doit être rempli")
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
