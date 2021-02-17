<?php

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiFilter;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\BooleanFilter;
use ApiPlatform\Core\Annotation\ApiResource;
use ApiPlatform\Core\Annotation\ApiSubresource;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="type", type="string")
 * @ORM\DiscriminatorMap({"user" = "User", "apprenant" = "Apprenant", "admin" = "Admin", "formateur" = "Formateur", "cm"="Cm"})
 * @UniqueEntity(
 *     fields={"login"},
 *     message = "le login existe deja"
 * )
 * @ApiResource (
 *     routePrefix="/admin/",
 *      attributes={
 *          "security"="is_granted('ROLE_ADMIN')",
 *          "security_message"="Vous n'avez pas access à cette Ressource"
 *      },
 *     normalizationContext={"groups"={"user:read"}},
 *     denormalizationContext={"groups"={"user:write"}},
 *     collectionOperations={
 *                 "add_user"={
 *                      "method"="POST",
 *                      "route_name"="user_add"
 *                   }, "get"={
 *                      "method"="POST",
 *                      "route_name"="get"
 *                   }
 *     },
 *     itemOperations={
 *                  "search"={
 *                      "method"="GET",
 *                      "route_name"="search",
 *     "normalization_context"={"groups"={"getuserconnecte:read"}}
 *                   },
 *              "getitem"={
 *                      "method"="GET",
 *                      "path"="/users/{id}",
 *                      "normalization_context"={"groups"={"useritem:read"}}
 *                },
 *              "qrcode"={
 *                      "method"="GET",
 *                      "path"="/users/{id}/qrcode",
 *                      "normalization_context"={"groups"={"qrcode:read"}}
 *                },
 *              "update_user"={
 *                      "method"="PUT",
 *                      "route_name"="user_update",
 *                      "denormalization_context"={"groups"={"upuser:write"}}
 *                },
 *              "delete"={"method"="DELETE",
 *                      "path"="/users/{id}"}
 *          }
 * )
 * @ApiFilter(BooleanFilter::class, properties={"archive"})
 */
class User implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups ({"qrcode:read","getuserconnecte:read","principal:read","getalluser:read","user:read","useritem:read","apprenant:read","promo:write","promo:read","groupe:write","profil:read"})
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=180,unique=true)
     * @Assert\NotBlank(message = "Le login ne peut etre vide")
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","upuser:write","promo:write","user:read","user:write","profil:read","apprenant:read","formateur:read","useritem:read"})
     */
    private $login;

    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     * @Groups ({"password:write"})
     */
    private $password;

    /**
     * @ORM\ManyToOne(targetEntity=Profil::class, inversedBy="users")
     * @ApiSubresource()
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","user:read", "user:write","useritem:read"})
     */
    private $profil;

    /**
     * @SerializedName("password")
     */
    private $plainPassword;

    /**
     * @ORM\Column(name="archive", type="boolean", options={"default":false})
     */
    private $archive = false;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Donner le nom")
     * @Assert\Regex(
     *      pattern="/^[A-Z]+$/",
     *      message="Le nom est ecrit en lettre capitale"
     * )
     * @Groups ({"promo:write","user:read","groupe:write","user:write","profil:read","apprenant:read","formateur:read","profilssortie:read","promo:read","groupe:read","getbpromo:read"})
     * @Groups ({"comm:read","useritem:read"})
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","upuser:write","principal:read","getform:read"})
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Donner le prenom")
     * @Assert\Regex(
     *      pattern="/^[A-Z][a-z]+$/",
     *      message="Le prenom commence par une lettre majuscule"
     * )
     * @Groups ({"promo:write","user:read","groupe:write","user:write","profil:read","apprenant:read","formateur:read","profilssortie:read","promo:read","groupe:read","getbpromo:read"})
     * @Groups ({"comm:read","useritem:read"})
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","upuser:write","principal:read","getform:read"})
     */
    private $prenom;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Donner le telephone")
     * @Assert\Regex(
     *     pattern="/^7[7|6|8|0][0-9]{7}$/",
     *     message="Seuls les operateurs Tigo Expresso et Orange sont permis"
     * )
     * @Groups ({"promo:write","user:read","groupe:write","user:write","profil:read","apprenant:read","formateur:read"})
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","upuser:write","useritem:read"})
     */
    private $telephone;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "Donner l'adresse email")
     * @Groups ({"promo:write","user:read","user:write","profil:read","apprenant:read","formateur:read"})
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","upuser:write","useritem:read","attenteOne:read"})
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message = "donner votre adresse")
     * @Groups ({"upuser:write","useritem:read"})
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","promo:write","user:read","user:write","profil:read","apprenant:read","formateur:read"})
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     * @Assert\NotBlank(message = "Donner le genre")
     * @Assert\Regex(
     *     pattern="/F|M/",
     *     message="Mets F comme Feminin et M comme Masculin"
     * )
     * @Groups ({"upuser:write","useritem:read"})
     * @Groups ({"qrcode:read","getuserconnecte:read","getalluser:read","promo:write","user:read","user:write","profil:read","apprenant:read"})
     */
    private $genre;

    /**
     * @ORM\Column(type="blob", nullable=true)
     * @Groups ({"user:write","user:read"})
     * @Groups ({"getuserconnecte:read","upuser:write","useritem:read"})
     */
    private $avatar;

    /**
     * @ORM\OneToMany(targetEntity=Chat::class, mappedBy="user")
     */
    private $chats;

    public function __construct()
    {
        $this->chats = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLogin(): ?string
    {
        return $this->login;
    }

    public function setLogin(string $login): self
    {
        $this->login = $login;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->login;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_'.$this->profil->getLibelle();

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
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getProfil(): ?Profil
    {
        return $this->profil;
    }

    public function setProfil(?Profil $profil): self
    {
        $this->profil = $profil;

        return $this;
    }

    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getArchive(): ?bool
    {
        return $this->archive;
    }

    public function setArchive(bool $archive): self
    {
        $this->archive = $archive;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }

    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getAvatar()
    {
        if($this->avatar)
        {
            $avatar_str= stream_get_contents($this->avatar);
            return base64_encode($avatar_str);
        }
        return null;
    }

    public function setAvatar($avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * @return Collection|Chat[]
     */
    public function getChats(): Collection
    {
        return $this->chats;
    }

    public function addChat(Chat $chat): self
    {
        if (!$this->chats->contains($chat)) {
            $this->chats[] = $chat;
            $chat->setUser($this);
        }

        return $this;
    }

    public function removeChat(Chat $chat): self
    {
        if ($this->chats->removeElement($chat)) {
            // set the owning side to null (unless already changed)
            if ($chat->getUser() === $this) {
                $chat->setUser(null);
            }
        }

        return $this;
    }
}
