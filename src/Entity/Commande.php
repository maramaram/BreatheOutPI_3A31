<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\CommandeRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CommandeRepository::class)]
#[ApiResource]
class Commande
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le statut ne peut pas être vide.")]
    #[Assert\Choice(
        choices: ["en attente", "en route", "arrivée"],
        message: "Le statut doit être l'une des valeurs : en attente, en route, arrivée"
    )]
    private ?string $statut = null;

    #[ORM\ManyToOne(inversedBy: 'commande')]
    private ?Livreur $livreur = null;

    #[ORM\ManyToOne(inversedBy: 'user')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatut(): ?string
    {
        return $this->statut;
    }

    public function setStatut(string $statut): static
    {
        $this->statut = $statut;

        return $this;
    }

    public function getLivreur(): ?Livreur
    {
        return $this->livreur;
    }

    public function setLivreur(?Livreur $livreur): static
    {
        $this->livreur = $livreur;

        return $this;
    }

    // Supprimez également les méthodes associées à la relation avec Panier

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
    
}
