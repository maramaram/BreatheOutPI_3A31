<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $prix_tot = null;

    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'paniers')]
    private Collection $produits_id;

    public function __construct()
    {
        $this->produits_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPrixTot(): ?int
    {
        return $this->prix_tot;
    }

    public function setPrixTot(int $prix_tot): static
    {
        $this->prix_tot = $prix_tot;

        return $this;
    }

    /**
     * @return Collection<int, produits>
     */
    public function getProduitsId(): Collection
    {
        return $this->produits_id;
    }

    public function addProduitsId(produits $produitsId): static
    {
        if (!$this->produits_id->contains($produitsId)) {
            $this->produits_id->add($produitsId);
        }

        return $this;
    }

    public function removeProduitsId(produits $produitsId): static
    {
        $this->produits_id->removeElement($produitsId);

        return $this;
    }

    
}
