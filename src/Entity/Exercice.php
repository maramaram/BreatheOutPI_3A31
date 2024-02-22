<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\ExerciceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExerciceRepository::class)]
class Exercice
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
        Assert\Length(min: 10, minMessage: "Votre nom doit contenir au moins 10 caractères.")
    ]
    #[ORM\Column(type: Types::TEXT)]
    private ?string $des = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[Assert\Choice(choices: ["Pectoraux", "Epaules", "Biceps", "Triceps", "Abdos", "Dos", "Quadriceps", "Ischio-jambiers", "Fessiers", "Mollets"], message: "Veuillez choisir parmi Pectoraux, Epaules, Biceps, Triceps, Abdos, Dos, Quadriceps, Ischio-jambiers, Fessiers ou Mollets")]
    private ?string $mc = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[Assert\Choice(choices: ["1", "2", "3"], message: "Veuillez choisir parmi 1, 2 ou 3")]
    private ?string $nd = null;

    #[
        Assert\NotNull(message: "Veuillez télécharger une image."),
        Assert\Image(
            minWidth: 200,
            maxWidth: 400,
            minHeight: 200,
            maxHeight: 400,
            minWidthMessage: "La largeur de l'image doit être d'au moins 200 pixels.",
            maxWidthMessage: "La largeur de l'image ne doit pas dépasser 400 pixels.",
            minHeightMessage: "La hauteur de l'image doit être d'au moins 200 pixels.",
            maxHeightMessage: "La hauteur de l'image ne doit pas dépasser 400 pixels."
        )
    ]
    #[ORM\Column(length: 255)]
    private ?string $img = null;

    #[
        Assert\NotNull(message: "Veuillez télécharger un gif."),
        Assert\File(
            mimeTypes: ["image/gif"],
            mimeTypesMessage: "Veuillez télécharger un fichier GIF valide"
        )
    ]
    #[ORM\Column(length: 255)]
    private ?string $gif = null;

    #[ORM\ManyToMany(targetEntity: Defi::class, mappedBy: 'ex')]
    private Collection $defis;



    public function __construct()
    {
        $this->defis = new ArrayCollection();
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

    public function getMc(): ?string
    {
        return $this->mc;
    }

    public function setMc(string $mc): static
    {
        $this->mc = $mc;

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

    public function getImg(): ?string
    {
        return $this->img;
    }

    public function setImg(string $img): static
    {
        $this->img = $img;

        return $this;
    }

    public function getGif(): ?string
    {
        return $this->gif;
    }

    public function setGif(string $gif): static
    {
        $this->gif = $gif;

        return $this;
    }

    /**
     * @return Collection<int, Defi>
     */
    public function getDefis(): Collection
    {
        return $this->defis;
    }

    public function addDefi(Defi $defi): static
    {
        if (!$this->defis->contains($defi)) {
            $this->defis->add($defi);
            $defi->addEx($this);
        }

        return $this;
    }

    public function removeDefi(Defi $defi): static
    {
        if ($this->defis->removeElement($defi)) {
            $defi->removeEx($this);
        }

        return $this;
    }

    public function __toString()
    {
        return $this->nom;
    }
   
}
