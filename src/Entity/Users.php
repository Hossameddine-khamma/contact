<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\HttpFoundation\File\File;


/**
 * @ORM\Entity(repositoryClass=UsersRepository::class)
 * @UniqueEntity(fields={"email"}, message="l'email que vous avez indiqué est deja utilisé")
 * @Vich\Uploadable
 */
class Users implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string" , nullable=true)
     */
    private $password;

    /**
     * @Assert\EqualTo(propertyPath = "password", message="veuillez saisir le même mot de passe")
     */
    public $confirm_password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Nom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Prenom;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ville;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Metier;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Tags;

    /**
     * @ORM\OneToMany(targetEntity=Contacts::class, mappedBy="user", orphanRemoval=true)
     */
    private $Contacts;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $activation_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reset_token;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $linkedinID;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $image;

    /**
     * @Vich\UploadableField(mapping="users", fileNameProperty="image")
     * @var File
     */
    private $imageFile;

    /**
     * @ORM\Column(type="datetime")
     * @var \DateTime
     */
    private $updatedAt;

    public function __construct()
    {
        $this->Contacts = new ArrayCollection();
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
    public function getUsername(): string
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
     * @see UserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getNom(): ?string
    {
        return $this->Nom;
    }

    public function setNom(?string $Nom): self
    {
        $this->Nom = $Nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->Prenom;
    }

    public function setPrenom(?string $Prenom): self
    {
        $this->Prenom = $Prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getVille(): ?string
    {
        return $this->ville;
    }

    public function setVille(?string $ville): self
    {
        $this->ville = $ville;

        return $this;
    }

    public function getMetier(): ?string
    {
        return $this->Metier;
    }

    public function setMetier(?string $Metier): self
    {
        $this->Metier = $Metier;

        return $this;
    }

    public function getTags(): ?string
    {
        return $this->Tags;
    }

    public function setTags(?string $Tags): self
    {
        $this->Tags = $Tags;

        return $this;
    }

    /**
     * @return Collection|contacts[]
     */
    public function getContacts(): Collection
    {
        return $this->Contacts;
    }

    public function addContact(contacts $contact): self
    {
        if (!$this->Contacts->contains($contact)) {
            $this->Contacts[] = $contact;
            $contact->setUser($this);
        }

        return $this;
    }

    public function removeContact(contacts $contact): self
    {
        if ($this->Contacts->removeElement($contact)) {
            // set the owning side to null (unless already changed)
            if ($contact->getUser() === $this) {
                $contact->setUser(null);
            }
        }

        return $this;
    }

    public function getActivationToken(): ?string
    {
        return $this->activation_token;
    }

    public function setActivationToken(?string $activation_token): self
    {
        $this->activation_token = $activation_token;

        return $this;
    }

    public function getResetToken(): ?string
    {
        return $this->reset_token;
    }

    public function setResetToken(?string $reset_token): self
    {
        $this->reset_token = $reset_token;

        return $this;
    }

    public function getLinkedinID(): ?string
    {
        return $this->linkedinID;
    }

    public function setLinkedinID(?string $linkedinID): self
    {
        $this->linkedinID = $linkedinID;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    public function setImageFile(File $image = null)
    {
        $this->imageFile = $image;

        // VERY IMPORTANT:
        // It is required that at least one field changes if you are using Doctrine,
        // otherwise the event listeners won't be called and the file is lost
        if ($image) {
            // if 'updatedAt' is not defined in your entity, use another property
            $this->updatedAt = new \DateTime('now');
        }
    }

    public function setUpdateAt(DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getImageFile()
    {
        return $this->imageFile;
    }

    public function serialize() {

        return serialize(array(
        $this->id,
        $this->email,
        $this->password,
        ));
        
        }
        
        public function unserialize($serialized) {
        
        list (
        $this->id,
        $this->email,
        $this->password,
        ) = unserialize($serialized);
        }

}
