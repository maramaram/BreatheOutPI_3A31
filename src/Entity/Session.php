<?php

namespace App\Entity;

use App\Repository\SessionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: SessionRepository::class)]
class Session
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\GreaterThan(value: "today", message: "La date doit être supérieure à la date actuelle")]
    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[Assert\Choice(choices: ["1", "2", "3"], message: "Veuillez choisir parmi 1 , 2 ou 3")]
    #[ORM\Column(length: 255)]
    private ?string $type = null;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    
    #[ORM\ManyToOne(inversedBy: 'sessions')]
    private ?User $coach = null;

    #[Assert\GreaterThanOrEqual(10)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[ORM\Column]
    private ?int $cap = null;

    #[ORM\OneToMany(targetEntity: Reservation::class, mappedBy: 'session')]
    private Collection $reservations;

    #[ORM\Column(length: 255)]
    private ?string $vid = null;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[
        Assert\Length(min: 2, minMessage: "Votre nom doit contenir au moins 2 caractères.")
    ] 
    #[ORM\Column(length: 255)]
    private ?string $des = null;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getCoach(): ?User
    {
        return $this->coach;
    }

    public function setCoach(?User $coach): static
    {
        $this->coach = $coach;

        return $this;
    }

    public function getCap(): ?int
    {
        return $this->cap;
    }

    public function setCap(int $cap): static
    {
        $this->cap = $cap;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): static
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setSession($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): static
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getSession() === $this) {
                $reservation->setSession(null);
            }
        }

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

    public function getVid(): ?string
    {
        return $this->vid;
    }

    public function setVid(string $vid): static
    {
        $this->vid = $vid;

        return $this;
    }

    public function getDes(): ?string
    {
        return $this->des;
    }

    public function setDes(string $des): static
    {
        $this->des = $des;

        return $this;
    }
}
