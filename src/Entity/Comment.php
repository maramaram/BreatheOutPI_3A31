<?php

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\CommentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

#[ORM\Entity(repositoryClass: CommentRepository::class)]
class Comment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'commentaire')]
    #[ORM\JoinColumn(nullable: false)]

    private ?User $user = null;

    #[
        Assert\Length(min: 10, minMessage: "Votre nom doit contenir au moins 10 caractères.")
    ]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[ORM\Column(length: 255)]
    private ?string $contenu = null;

    #[Assert\GreaterThanOrEqual(0)]
    #[Assert\NotBlank(message: "Ce champ ne peut pas être vide")]
    #[ORM\Column(type: 'integer')]
    private ?int $nbLikes = 0;

    #[ORM\ManyToOne(inversedBy: 'comment')]
    private ?Post $post = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getContenu(): ?string
    {
        return $this->contenu;
    }

    public function setContenu(string $contenu): static
    {
        $this->contenu = $contenu;

        return $this;
    }

    public function getnbLikes(): ?int
    {
        return $this->nbLikes;
    }

    public function setnbLikes(int $nbLikes): static
    {
        $this->nbLikes = $nbLikes;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }





}
