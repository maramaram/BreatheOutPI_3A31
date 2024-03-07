<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements PasswordAuthenticatedUserInterface,UserInterface
{


    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"First Name is required")]
    #[Assert\Length(
        min: 3,
        max: 10,
    )]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'the first name must only contains characters[a-z].'
    )]
    private ?string $nom = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 3,
        max: 10,
        exactMessage: 'less characters for your last name'
    )]
    #[Assert\Regex(
        pattern: '/^[a-z]+$/i',
        message: 'the last name must only contains characters[a-z].'
    )]
    #[Assert\NotBlank(message:"Last Name is required")]
    private ?string $prenom = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Mail please")]
    #[Assert\Email(
        message: 'The email {{ value }} is not a valid email.',
    )]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message:"Password is required")]
    #[Assert\Regex(
        pattern: '/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()-_=+{};:,<.>]).{8,}$/',
        message: 'Your password is too weak. Please include at least one uppercase letter, one lowercase letter, one digit, and one special character.'
    )]
    private ?string $pwd = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotBlank(message:"Birthday date is required")]
    #[Assert\LessThan("today")]
    private ?\DateTimeInterface $date_N = null;

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    #[Assert\Length(
        min: 15,
        max: 150,
        exactMessage: 'less characters for your last name'
    )]
    #[Assert\NotBlank(message:"Adress is required")]
    private ?string $adress = null;

    #[
        Assert\Image(
            minWidth: 200,
            maxWidth: 600,
            minHeight: 200,
            maxHeight: 120000,
            minWidthMessage: "La largeur de l'image doit être d'au moins 200 pixels.",
            maxWidthMessage: "La largeur de l'image ne doit pas dépasser 600 pixels.",
            minHeightMessage: "La hauteur de l'image doit être d'au moins 200 pixels.",
            maxHeightMessage: "La hauteur de l'image ne doit pas dépasser 120000 pixels."
        )
    ]
    #[ORM\Column(length: 255)]
    private ?string $photo = null;

    #[ORM\Column(length: 255)]
    private ?string $status = "inactive";

    #[ORM\Column(length: 255)]
    private ?string $num_tel = null;


    #[ORM\OneToMany(mappedBy: 'Author', targetEntity: Post::class)]
    private Collection $posts;



    public function __construct()
    {
        $this->commentaire = new ArrayCollection();
        $this->posts = new ArrayCollection();
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getCommentaire(): Collection
    {
        return $this->commentaire;
    }

    public function addCommentaire(Comment $commentaire): static
    {
        if (!$this->commentaire->contains($commentaire)) {
            $this->commentaire->add($commentaire);
            $commentaire->setIdUser($this);
        }

        return $this;
    }

    public function removeCommentaire(Comment $commentaire): static
    {
        if ($this->commentaire->removeElement($commentaire)) {
            // set the owning side to null (unless already changed)
            if ($commentaire->getIdUser() === $this) {
                $commentaire->setIdUser(null);
            }
        }

        return $this;
    }



    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setAuthor($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getAuthor() === $this) {
                $post->setAuthor(null);
            }
        }

        return $this;
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

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): static
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPwd(): ?string
    {
        return $this->pwd;
    }

    public function setPwd(string $pwd): static
    {
        $this->pwd = $pwd;

        return $this;
    }

    public function getDateN(): ?\DateTimeInterface
    {
        return $this->date_N;
    }

    public function setDateN(\DateTimeInterface $date_N): static
    {
        $this->date_N = $date_N;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }
    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(string $photo): static
    {
        $this->photo = $photo;

        return $this;
    }
    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $adress): static
    {
        $this->adress = $adress;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->pwd;
        // TODO: Implement getPassword() method.
    }

    public function getRoles()
    {
        // Return an array of roles for the user, e.g., ['ROLE_USER']
        return ['ROLE_USER'];
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
    }

    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->getEmail();
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function getNum_tel(): ?string
    {
        return $this->num_tel;
    }

    public function setNum_tel(string $num_tel): static
    {
        $this->num_tel = $num_tel;

        return $this;
    }
    public function getNumTel(): ?string
    {
        return $this->num_tel;
    }

    public function setNumTel(string $num_tel): static
    {
        $this->num_tel = $num_tel;

        return $this;
    }
    public function __toString()
    {
        return $this->id ." ". $this->nom;
    }
}
