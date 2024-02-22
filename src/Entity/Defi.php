<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\DefiRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DefiRepository::class)]
class Defi
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[
        Assert\Length(min: 2, minMessage: "Votre nom doit contenir au moins 2 caractères.")
    ]        
    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[
        Assert\Length(
            min: 10,
            minMessage: "Votre nom doit contenir au moins 10 caractères.",
            max: 70,
            maxMessage: "Votre nom ne peut pas dépasser 70 caractères."
        )
    ]      
    #[ORM\Column(length: 255)]
    private ?string $des = null;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[Assert\Choice(choices: ["1", "2", "3"], message: "Veuillez choisir parmi 1, 2 ou 3")]
    #[ORM\Column(length: 255)]
    private ?string $nd = null;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[
        Assert\Count(
            min: 1,
            minMessage: "Vous devez sélectionner au moins un exercice."
        )
    ]
    #[ORM\ManyToMany(targetEntity: Exercice::class, inversedBy: 'defis')]
    private Collection $ex;

    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[
        Assert\GreaterThan(
            value: 0,
            message: "Le nombre de jours doit être supérieur à zéro."
        ),
        Assert\LessThanOrEqual(
            value: 30,
            message: "Le nombre de jours ne peut pas dépasser 30."
        )
    ]
    #[ORM\Column]
    private ?int $nbj = null;

    public function __construct()
    {
        $this->ex = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

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

    public function getNd(): ?string
    {
        return $this->nd;
    }

    public function setNd(string $nd): static
    {
        $this->nd = $nd;

        return $this;
    }

    /**
     * @return Collection<int, Exercice>
     */
    public function getEx(): Collection
    {
        return $this->ex;
    }

    public function addEx(Exercice $ex): static
    {
        if (!$this->ex->contains($ex)) {
            $this->ex->add($ex);
        }

        return $this;
    }

    public function removeEx(Exercice $ex): static
    {
        $this->ex->removeElement($ex);

        return $this;
    }

    public function getNbj(): ?int
    {
        return $this->nbj;
    }

    public function setNbj(int $nbj): static
    {
        $this->nbj = $nbj;

        return $this;
    }
}
