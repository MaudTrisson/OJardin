<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email(message: "adresse mail non valide.")]
    #[Assert\NotBlank(message: "L'email ne doit pas être vide")]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\Length(
        min: 6,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères.'
    )]
    #[Assert\Regex(
        pattern: '/^(?=.*[a-zA-Z])(?=.*[!@#$%^&*()_+])(?=.*\d).+$/',
        message: 'Le mot de passe doit contenir au moins une lettre, au moins un chiffre et au moins un caractère spécial.'
    )]
    #[Assert\NotBlank(message: "Le password ne doit pas être vide")]
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string $lastname = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: GardenUser::class)]
    private Collection $gardenUsers;

    public function __construct()
    {
        $this->gardenUsers = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->email;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * @return Collection<int, GardenUser>
     */
    public function getGardenUsers(): Collection
    {
        return $this->gardenUsers;
    }

    public function addGardenUser(GardenUser $gardenUser): self
    {
        if (!$this->gardenUsers->contains($gardenUser)) {
            $this->gardenUsers->add($gardenUser);
            $gardenUser->setUser($this);
        }

        return $this;
    }

    public function removeGardenUser(GardenUser $gardenUser): self
    {
        if ($this->gardenUsers->removeElement($gardenUser)) {
            // set the owning side to null (unless already changed)
            if ($gardenUser->getUser() === $this) {
                $gardenUser->setUser(null);
            }
        }

        return $this;
    }

}
