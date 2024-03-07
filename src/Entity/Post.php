<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;


    #[
        Assert\Length(max: 10, minMessage: "Votre title doit contenir au plus 10 caractères.")
    ]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $title = null;


    #[
        Assert\Length(min: 10, minMessage: "Votre content doit contenir au moin 10 caractères.")
    ]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $content = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $date = null;

    
    #[
        Assert\NotNull(message: "Veuillez télécharger une image."),
        Assert\Image(
            minWidth: 200,
            maxWidth: 1200,
            minHeight: 200,
            maxHeight: 1200,
            minWidthMessage: "La largeur de l'image doit être d'au moins 200 pixels.",
            maxWidthMessage: "La largeur de l'image ne doit pas dépasser 400 pixels.",
            minHeightMessage: "La hauteur de l'image doit être d'au moins 200 pixels.",
            maxHeightMessage: "La hauteur de l'image ne doit pas dépasser 400 pixels."
        )
    ]

    #[ORM\Column(length: 255)]
    private ?string $image = null;

    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class, cascade: ['remove'])]
    private Collection $comment;

    #[ORM\Column]
    private ?int $views = null;

    #[ORM\ManyToOne(inversedBy: 'posts')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $Author = null;

    public function __construct()
    {
        $this->comment = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getAuthor(): ?User
    {
        return $this->Author;
    }

    public function setAuthor(?User $Author): static
    {
        $this->Author = $Author;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComment(): Collection
    {
        return $this->comment;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comment->contains($comment)) {
            $this->comment->add($comment);
            $comment->setPost($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comment->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getPost() === $this) {
                $comment->setPost(null);
            }
        }

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): static
    {
        $this->views = $views;

        return $this;
    }

    public function __toString()
    {
        return $this->id;
    }

    
}
