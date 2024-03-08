<?php

namespace App\Entity;

use App\Repository\PanierRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PanierRepository::class)]
class Panier
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: Produits::class, inversedBy: 'paniers')]
    #[Assert\Count(
        min: 1,
        minMessage: "Please choose at least one product."
    )]
    private Collection $listeproduits;

    #[ORM\Column]
    private ?int $prix_tot =0;

    #[ORM\Column(type: 'integer')]
    private ?int $quantite = 0;

    #[ORM\OneToOne(inversedBy: 'panier',targetEntity: 'User')]
    #[ORM\JoinColumn(name: 'user_id',referencedColumnName: 'id')]
    private ?User $user = null;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->listeproduits = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, Produits>
     */
    public function getListeproduits(): Collection
    {
        return $this->listeproduits;
    }

    public function getQuantite(): int
    {
        return count($this->listeproduits);
    }

    public function setQuantite(?int $quantite): static
    {
        $this->quantite = $quantite;

        return $this;
    }
    public function addListeproduit(Produits $listeproduit): static
    {
        // Vérifie si le produit est déjà dans le panier
        $produitDansPanier = $this->listeproduits->contains($listeproduit);

        if (!$produitDansPanier) {
            // Vérifiez la quantité en stock du produit
            $quantiteEnStock = $listeproduit->getQuantiteStock();

            if ($quantiteEnStock > 0) {
                // Ajoute le produit à la liste des produits du panier
                $this->listeproduits->add($listeproduit);

                // Diminue la quantité en stock du produit
                $listeproduit->setQuantiteStock($quantiteEnStock - 1);

                // Mettez à jour la quantité totale du panier
                $this->updateTotalQuantity();
            } else {
                // Gérez le cas où la quantité en stock est épuisée
                // Vous pouvez lever une exception, afficher un message, etc.
            }
        }

        return $this;
    }




private function updateTotalQuantity(): void
{
    // Réinitialise la quantité totale du panier à 0
    $this->quantite = 0;

    // Parcourt tous les produits dans la liste du panier
    foreach ($this->listeproduits as $produit) {
        // Ajoute la quantité minimale entre la quantité en stock et 1
        $this->quantite += min($produit->getQuantiteStock(), 1);
    }
}



    public function removeListeproduit(Produits $listeproduit): static
    {
        $this->listeproduits->removeElement($listeproduit);

        return $this;
    }

    public function getPrixTot(): int
    {
        $prixTot = 0;

        foreach ($this->listeproduits as $produit) {
            $prixTot += $produit->getPrix();
        }

        return $prixTot;
    }

    public function setPrixTot(int $prixTot): static
    {
        $this->prixTot = $prixTot;

        return $this;
    }

    public function getPrix_tot(): int
    {
        $prixTot = 0;

        foreach ($this->listeproduits as $produit) {
            $prixTot += $produit->getPrix();
        }

        return $prixTot;
    }

    public function setPrix_tot(int $prixTot): static
    {
        $this->prixTot = $prixTot;

        return $this;
    }

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
